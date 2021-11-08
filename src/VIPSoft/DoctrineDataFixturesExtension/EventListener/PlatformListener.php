<?php
/**
 * @copyright 2014 Anthon Pang
 * @license MIT
 */

namespace VIPSoft\DoctrineDataFixturesExtension\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\DBAL\Platforms\MySqlPlatform;

/**
 * Platform listener
 *
 * @author Anthon Pang <apang@softwaredevelopment.ca>
 */
class PlatformListener implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            'preTruncate',
            'postTruncate',
        );
    }

    public function preTruncate(LifecycleEventArgs $args): void
    {
        $connection = $args->getObjectManager()->getConnection();
        $platform   = $connection->getDatabasePlatform();

        if ($platform instanceof MySqlPlatform) {
            $connection->exec('SET foreign_key_checks = 0;');
        }
    }

    public function postTruncate(LifecycleEventArgs $args): void
    {
        $connection = $args->getObjectManager()->getConnection();
        $platform   = $connection->getDatabasePlatform();

        if ($platform instanceof MySqlPlatform) {
            $connection->exec('SET foreign_key_checks = 1;');
        }
    }
}
