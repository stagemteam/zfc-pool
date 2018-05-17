<?php
/**
 * @category Stagem
 * @package Stagem_ZfcPool
 * @author Serhii Popov <popow.serhii@gmail.com>
 */

namespace Stagem\ZfcPool\Service\Factory;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Stagem\ZfcPool\Model\Pool;
use Stagem\ZfcPool\Service\PoolService;

class PoolServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $poolService = new PoolService();

        // @todo продумати реалізацію Pool відносно цих заміток @link https://trello.com/c/vjIQacQX/12-проблема-pool
        $om = $container->get(EntityManager::class);
        $pool = $om->find(Pool::class, 1);
        $poolService->setCurrent($pool);

        return $poolService;
    }
}