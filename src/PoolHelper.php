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

use Stagem\ZfcPool\Model\Pool;
use Stagem\ZfcPool\Service\PoolService;

class PoolHelper
{
    /**
     * @var PoolService
     */
    protected $poolService;

    /**
     * @var Pool[]
     */
    protected $pools;

    public function __construct(PoolService $poolService)
    {
        $this->poolService = $poolService;
    }

    /**
     * @return Model\Pool
     */
    public function current()
    {
        return $this->poolService->getCurrent();
    }

    /**
     * @return Pool[]
     */
    public function getPools()
    {
        if (!$this->pools) {
            $this->pools = $this->poolService->getActivePools();
        }
        return $this->pools;
    }

    public function __invoke()
    {
        return $this;
    }
}