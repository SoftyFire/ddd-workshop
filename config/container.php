<?php /** @noinspection ForgottenDebugOutputInspection */

use Billing\Infrastructure\DI\Container;

$definitions = [
//    \Billing\Domain\Repository\InvoiceRepository::class => \Billing\Infrastructure\Repository\JsonInvoiceRepository::class,

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

    \Doctrine\Common\Persistence\ObjectManager::class => function (Container $container) {
        $configuration = new Doctrine\ORM\Configuration();
        $configuration->setMetadataDriverImpl(new Doctrine\ORM\Mapping\Driver\XmlDriver(dirname(__DIR__) . '/mapping'));
        $configuration->setProxyDir(sys_get_temp_dir());
        $configuration->setProxyNamespace('ProxyExample');
        $configuration->setAutoGenerateProxyClasses(Doctrine\ORM\Proxy\ProxyFactory::AUTOGENERATE_ALWAYS);

        \Doctrine\DBAL\Types\Type::addType('uuid', Ramsey\Uuid\Doctrine\UuidType::class);
        \Doctrine\DBAL\Types\Type::addType('currency', Billing\Infrastructure\Repository\Doctrine\CurrencyType::class);
        \Doctrine\DBAL\Types\Type::addType('email', \Billing\Infrastructure\Repository\Doctrine\EmailAddressType::class);

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
