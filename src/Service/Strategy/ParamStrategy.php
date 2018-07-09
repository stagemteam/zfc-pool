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

namespace Stagem\ZfcPool\Service\Strategy;

use Doctrine\ORM\EntityManager;
use Stagem\ZfcSystem\Config\SysConfig;
use Zend\Session\Container;
use Stagem\ZfcPool\Service\PoolService;
use Popov\ZfcCurrent\CurrentHelper;

class ParamStrategy
{
    /**
     * @var CurrentHelper
     */
    protected $currentHelper;

    /**
     * @var EntityManager
     */
    protected $entityManager;
    
    protected $config;

    public function __construct(EntityManager $entityManager, CurrentHelper $currentHelper, array $config)
    {
        $this->config = $config;
        $this->currentHelper = $currentHelper;
        $this->entityManager = $entityManager;
    }

    public function getPool()
    {
        $params = $this->currentHelper->currentRouteParams();
        #$request = $this->currentHelper->currentRequest();
        #$params = $request->getQueryParams();

        $urlParameter = $this->config['pool']['general']['url_parameter'];
        $poolClass = $this->config['pool']['general']['pool_class'];
        $poolProp = $this->config['pool']['general']['pool_property'];
        $poolDefault = $this->config['pool']['general']['pool_default'];


        // do not remember Admin Pool in session
        if (isset($params[$urlParameter]) && ($params[$urlParameter] === PoolService::POOL_ADMIN)) {
            return (new $poolClass())
                ->setId(PoolService::POOL_ADMIN)
                ->setName('Default Configuration');
        }

        $session = new Container('Stagem\ZfcPool');

        if (isset($params[$urlParameter])) {
            $poolValue = $params[$urlParameter];
            $pool = $this->entityManager->getRepository($poolClass)->findOneBy([$poolProp => $poolValue]);
        } elseif ($session->offsetExists(PoolService::SESSION_KEY)) {
            $pool = $this->entityManager->find($poolClass, $session->offsetGet(PoolService::SESSION_KEY));
        } else {
            $pool = $this->entityManager->getRepository($poolClass)->findOneBy([$poolProp => $poolDefault]);
        }

        $session->offsetSet(PoolService::SESSION_KEY, $pool->getId());

        return $pool;
    }
}
