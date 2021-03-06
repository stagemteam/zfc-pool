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

    protected $session;

    public function __construct(EntityManager $entityManager, CurrentHelper $currentHelper, array $config)
    {
        $this->config = $config;
        $this->currentHelper = $currentHelper;
        $this->entityManager = $entityManager;
        $this->session = new Container('Stagem\ZfcPool');
    }

    public function getPool()
    {
        $params = $this->currentHelper->currentRouteParams();
        $headers = $this->currentHelper->currentHeaders();
        #$request = $this->currentHelper->currentRequest();
        #$params = $request->getQueryParams();

        $headerParameter = $this->config['pool']['general']['header_parameter'] ?? null;
        $urlParameter = $this->config['pool']['general']['url_parameter'] ?? null;
        $poolClass = $this->config['pool']['general']['pool_class'];
        $poolProp = $this->config['pool']['general']['pool_property'];
        $poolDefault = $this->config['pool']['general']['pool_default'];

        if ($urlParameter && isset($params[$urlParameter])) {
            // Pool parameter passed as Route param
            $poolValue = $params[$urlParameter];
            $pool = ($poolValue === PoolService::POOL_ADMIN)
                ? PoolService::getAdminPool($poolClass)
                : $this->entityManager->getRepository($poolClass)->findOneBy([$poolProp => $poolValue]);
        } elseif($headerParameter && isset($headers[$headerParameter])) {
            // Pool parameter passed as Header
            $poolValue = $headers[$headerParameter][0];
            $pool = ($poolValue === PoolService::POOL_ADMIN)
                ? PoolService::getAdminPool($poolClass)
                : $this->entityManager->getRepository($poolClass)->findOneBy([$poolProp => $poolValue]);
        } elseif ($this->session->offsetExists(PoolService::SESSION_KEY)) {
            // Pool parameter saved in Session
            $poolValue = $this->session->offsetGet(PoolService::SESSION_KEY);
            $pool = ($poolValue === PoolService::POOL_ADMIN)
                ? PoolService::getAdminPool($poolClass)
                : $this->entityManager->find($poolClass, $this->session->offsetGet(PoolService::SESSION_KEY));
        } else {
            // Retrieve default pool
            $pool = (php_sapi_name() == 'cli')
                ? PoolService::getAdminPool($poolClass)
                : $this->entityManager->getRepository($poolClass)->findOneBy([$poolProp => $poolDefault]);
        }

        $meta = $this->entityManager->getClassMetadata($poolClass);
        $identifier = $meta->getSingleIdentifierFieldName();

        $this->session->offsetSet(PoolService::SESSION_KEY, $pool->{'get' . ucfirst($identifier)}());

        return $pool;
    }
}
