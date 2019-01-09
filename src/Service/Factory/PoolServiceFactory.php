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

namespace Stagem\ZfcPool\Service\Factory;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Stagem\ZfcPool\Service\PoolService;
use Zend\Stdlib\Exception\RuntimeException;

class PoolServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        // circular dependency in SysConfig
        //$sysConfig = $container->get(SysConfig::class);
        $config = $container->get('config');

        // If there is no strategy then Admin Pool will be used
        $strategyName = $config['pool']['general']['strategy'] ?? null;

        if (!is_null($strategyName) && !$container->has($strategyName)) {
            throw new RuntimeException(sprintf('There is no registered pool strategy with name "%s"', $strategyName));
        }

        $pool = is_null($strategyName)
            ? PoolService::getAdminPool($config['pool']['general']['pool_class'])
            : $container->get($strategyName)->getPool();

        $poolService = (new PoolService($config['pool']['general']['pool_class']))
            ->setCurrent($pool);

        return $poolService;
    }
}