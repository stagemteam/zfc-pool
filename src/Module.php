<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2018 Stagem Team
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Stagem
 * @package Stagem_ZfcPool
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Stagem\ZfcPool;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\MvcEvent;
use Stagem\ZfcPool\Model\Filter\PoolFilter;

class Module
{
    public function getConfig()
    {
        $config = include __DIR__ . '/../config/module.config.php';
        $config['service_manager'] = $config['dependencies'];
        unset($config['dependencies']);

        return $config;
    }

    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $container = $app->getServiceManager();
        $eventManager = $app->getEventManager();
        $sharedEvents = $eventManager->getSharedManager();

        $sharedEvents->attach(AbstractController::class, MvcEvent::EVENT_DISPATCH, function(MvcEvent $mvcEvent) use ($container) {
            /** @var PoolHelper $poolHelper */
            $om = $container->get(EntityManager::class);
            $poolHelper = $container->get(PoolHelper::class);
            $reader = $container->get(AnnotationReader::class);

            /** @var PoolFilter $filter */
            $filter = $om->getFilters()->enable('pool_filter');
            $filter->setAnnotationReader($reader);
            $filter->setPoolHelper($poolHelper);
        }, 1000);
    }
}
