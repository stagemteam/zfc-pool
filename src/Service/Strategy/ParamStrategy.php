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
    
    protected $sysConfig;

    public function __construct(EntityManager $entityManager, CurrentHelper $currentHelper, SysConfig $sysConfig)
    {
        $this->sysConfig = $sysConfig;
        $this->currentHelper = $currentHelper;
        $this->entityManager = $entityManager;
    }

    public function getPool()
    {
        $session = new Container('Stagem\ZfcPool');

        $request = $this->currentHelper->currentRequest();
        #$params = $this->currentHelper->currentRouteParams();
        $params = $request->getQueryParams();

        $poolClass = $this->sysConfig->getConfig('pool/general/pool_class');
        $poolProp = $this->sysConfig->getConfig('pool/general/pool_property');
        $poolDefault = $this->sysConfig->getConfig('pool/general/pool_default');

        if (isset($params[$this->sysConfig->getConfig('pool/general/url_parameter')])) {
            $poolValue = $params[$this->sysConfig->getConfig('pool/general/url_parameter')];
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
