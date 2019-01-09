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

use Popov\ZfcCore\Helper\Config;
use Popov\ZfcCurrent\CurrentHelper;
use Stagem\ZfcPool\Model\PoolInterface;
use Stagem\ZfcPool\Service\PoolService;

class PoolHelper
{
    /**
     * @var PoolService
     */
    protected $poolService;

    /**
     * @var CurrentHelper
     */
    protected $currentHelper;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var PoolInterface[]
     */
    protected $pools;

    public function __construct(PoolService $poolService, CurrentHelper $currentHelper, Config $config)
    {
        $this->poolService = $poolService;
        $this->currentHelper = $currentHelper;
        $this->config = $config;
    }

    /**
     * Get current pool
     * @return Model\PoolInterface
     */
    public function current()
    {
        return $this->poolService->getCurrent();
    }

    public function setCurrent($newPool)
    {
        $this->poolService->setCurrent($newPool);

        return $this;
    }

    /**
     * Get admin pool
     */
    public function admin()
    {
        return PoolService::getAdminPool($this->config->get('pool/general/pool_class'));
    }

    public function isSingleMode()
    {
        return $this->config->get('pool/general/pool_class') === PoolInterface::class;
    }

    /**
     * @return PoolInterface[]
     */
    public function getPools()
    {
        if (!$this->pools) {
            $this->pools = $this->poolService->getActivePools();
        }
        return $this->pools;
    }

    /**
     * Get Pool from Url otherwise return Admin Pool
     *
     * @return PoolInterface
     */
    public function findFromRoute()
    {
        $routeParams = $this->currentHelper->currentRouteParams();

        if (isset($routeParams[$this->config->get('pool/general/url_parameter')])) {
            $identifier = $routeParams[$this->config->get('pool/general/url_parameter')];
        }

        if (!isset($identifier) || PoolService::POOL_ADMIN == $identifier) {
            return PoolService::getAdminPool($this->config->get('pool/general/pool_class'));
        }

        $fieldIdentifier = $this->config->get('pool/general/pool_property');

        return $this->poolService->getRepository()->findOneBy([$fieldIdentifier => $identifier]);
    }

    public function __invoke()
    {
        return $this;
    }
}