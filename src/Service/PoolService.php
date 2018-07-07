<?php
/**
 * @package Stagem_Pool
 * @author Vlad Kozak <vk@stagem.com.ua>
 * @datetime: 15.08.2016 13:41
 */

namespace Stagem\ZfcPool\Service;

use Popov\ZfcCore\Service\DomainServiceAbstract;
use Stagem\ZfcPool\Model\Pool;
use Stagem\ZfcPool\Model\PoolInterface;
//use Stagem\ZfcPool\Model\Repository\PoolRepository;

/**
 * @method PoolRepository getRepository()
 */
class PoolService extends DomainServiceAbstract
{
    const SESSION_KEY = 'pool_id';

    //protected $entity = Pool::class;

    public function __construct(string $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @var PoolInterface
     */
    protected $current;

    /**
     * @param PoolInterface $current
     * @return $this
     */
    public function setCurrent(PoolInterface $current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * @return PoolInterface
     */
    public function getCurrent()
    {
        return $this->current;
    }

    public function getActivePools()
    {
        // @todo Add isEnable property to interface and filter by this value
        return $this->getRepository()->findBy([]);

    }
}