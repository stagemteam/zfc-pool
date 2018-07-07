<?php
/**
 * @category Stagem
 * @package Stagem_ZfcPool
 * @author Serhii Popov <popow.serhii@gmail.com>
 */

namespace Stagem\ZfcPool\Service\Factory;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;
//use Stagem\ZfcPool\Model\Pool;
use Stagem\ZfcPool\Service\PoolService;
use Stagem\ZfcSystem\Config\SysConfig;
use Zend\Stdlib\Exception\RuntimeException;

class PoolServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        // circular dependency in SysConfig
        //$sysConfig = $container->get(SysConfig::class);
        $config = $container->get('config');
        $strategyName = ucfirst($config['pool']['general']['strategy']);

        if (!$container->has($strategyName)) {
            throw new RuntimeException(sprintf('There is no registered pool strategy with name "%s"', $strategyName));
        }

        $strategy = $container->get($strategyName);
        $poolService = (new PoolService($config['pool']['general']['pool_class']))
            ->setCurrent($strategy->getPool());

        return $poolService;
    }
}