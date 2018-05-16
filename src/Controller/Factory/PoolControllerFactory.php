<?php
/**
 * @package Stagem_Pool
 * @author Vlad Kozak <vk@stagem.com.ua>
 * @datetime: 15.08.2016 13:41
 */
namespace Stagem\ZfcPool\Controller\Factory;

use Stagem\ZfcPool\Controller\PoolController;

class PoolControllerFactory
{
    public function __invoke($cm)
    {
        $sm = $cm->getServiceLocator();
        $controller = new PoolController();
        $controller->setServiceManager($sm);

        return $controller;
    }
}
