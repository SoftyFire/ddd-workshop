<?php /** @noinspection ForgottenDebugOutputInspection */

use Billing\Infrastructure\DI\Container;

$definitions = [
//    \Billing\Domain\Repository\InvoiceRepository::class => \Billing\Infrastructure\Repository\JsonInvoiceRepository::class,

    'http-request-bus' => function (Container $container) {
        return new \League\Tactician\CommandBus([
            $container->get(\Billing\Infrastructure\Middleware\ResponseFormatterMiddleware::class),
            $container->get(\Billing\Infrastructure\Middleware\ConvertToArrayExceptionHandler::class),
            $container->get(\Billing\Infrastructure\Middleware\LoadCommandFromRequestMiddleware::class),
            $container->get(\Billing\Infrastructure\Middleware\EventDispatcherMiddleware::class),
            $container->get(\League\Tactician\Handler\CommandHandlerMiddleware::class),
        ]);
    },

    \Billing\Infrastructure\Command\Customer\CreateHandler::class => function (Container $container) {
        return new \Billing\Infrastructure\Command\Customer\CreateHandler(
            $container->get(\Billing\Domain\Repository\CustomerRepository::class),
            $container->get(\Billing\Domain\Query\CustomerExists::class),
            $container->get(\Billing\Infrastructure\Event\EventStorage::class)
        );
    },

    \Billing\Infrastructure\Middleware\EventDispatcherMiddleware::class => function (Container $container) {
        return new \Billing\Infrastructure\Middleware\EventDispatcherMiddleware(
            $container->get(\Billing\Infrastructure\Event\EventStorage::class),
            $container->get(\Billing\Infrastructure\Event\DomainEventHandler::class)
        );
    },

    \League\Tactician\Handler\CommandHandlerMiddleware::class => function (Container $container) {
        return new \League\Tactician\Handler\CommandHandlerMiddleware(
            new \League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor(),
            new \Billing\Infrastructure\Middleware\NearbyHandlerLocator($container),
            new \League\Tactician\Handler\MethodNameInflector\HandleInflector()
        );
    },

    \Billing\Infrastructure\Event\DomainEventHandler::class => function (Container $container) {
        return new \Billing\Infrastructure\Event\DomainEventHandler([

            \Billing\Domain\Event\InvoiceWasCreatedEvent::class => [
                function (\Billing\Domain\Event\InvoiceWasCreatedEvent $event) {
                    error_log(sprintf(
                        ' -> Invoice %s for client "%s" has been created',
                        $event->getInvoice()->id()->toString(),
                        $event->getInvoice()->customer()->email()->toString()
                    ));
                },
            ],

        ], $container);
    },

    \Billing\Domain\Query\CustomerExists::class => function (Container $container) {
        return new \Billing\Infrastructure\Query\DoctrineBasedCustomerExists(
            $container->get(\Doctrine\ORM\EntityManagerInterface::class)
        );
    },

    \Billing\Domain\Repository\InvoiceRepository::class => function (Container $container) {
        return new \Billing\Infrastructure\Repository\DoctrineInvoiceRepository(
            $container->get(\Doctrine\Common\Persistence\ObjectManager::class)
        );
    },

    \Billing\Domain\Repository\CustomerRepository::class => function (Container $container) {
        return new \Billing\Infrastructure\Repository\DoctrineCustomerRepository(
            $container->get(\Doctrine\Common\Persistence\ObjectManager::class)
        );
    },

    \Billing\Infrastructure\Repository\JsonInvoiceRepository::class => function (Container $container) {
        return new \Billing\Infrastructure\Repository\JsonInvoiceRepository(
            dirname(__DIR__) . '/data/invoices.json',
            $container->get(\Billing\Infrastructure\Hydrator\InvoiceHydrator::class)
        );
    },

    \Doctrine\ORM\EntityManagerInterface::class => \Doctrine\Common\Persistence\ObjectManager::class,

    \Doctrine\Common\Persistence\ObjectManager::class => function (Container $container) {
        $configuration = new Doctrine\ORM\Configuration();
        $configuration->setMetadataDriverImpl(new Doctrine\ORM\Mapping\Driver\XmlDriver(dirname(__DIR__) . '/mapping'));
        $configuration->setProxyDir(sys_get_temp_dir());
        $configuration->setProxyNamespace('ProxyExample');
        $configuration->setAutoGenerateProxyClasses(Doctrine\ORM\Proxy\ProxyFactory::AUTOGENERATE_ALWAYS);

        \Doctrine\DBAL\Types\Type::addType('uuid', Ramsey\Uuid\Doctrine\UuidType::class);
        \Doctrine\DBAL\Types\Type::addType('currency', Billing\Infrastructure\Repository\Doctrine\CurrencyType::class);
        \Doctrine\DBAL\Types\Type::addType('email', \Billing\Infrastructure\Repository\Doctrine\EmailAddressType::class);
        \Doctrine\DBAL\Types\Type::addType(
            \Billing\Domain\Value\EmailAddress::class,
            \Billing\Infrastructure\Repository\Doctrine\EmailAddressType::class
        );

        return Doctrine\ORM\EntityManager::create(
            [
                'driverClass' => Doctrine\DBAL\Driver\PDOSqlite\Driver::class,
                'path'        => dirname(__DIR__) . '/data/db.sqlite',
            ],
            $configuration
        );
    },

    \Billing\Infrastructure\Hydrator\InvoiceHydrator::class => function (Container $container) {
        return new \Billing\Infrastructure\Hydrator\InvoiceHydrator(
            $container->get(\Billing\Infrastructure\Hydrator\LineItemHydrator::class)
        );
    },

    \Billing\Infrastructure\Hydrator\LineItemHydrator::class => function (Container $container) {
        return new \Billing\Infrastructure\Hydrator\LineItemHydrator(
            $container->get(\Billing\Infrastructure\Hydrator\ItemHydrator::class)
        );
    },
];

return new Container($definitions);
