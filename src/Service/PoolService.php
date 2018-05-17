<?php
/**
 * @package Stagem_Pool
 * @author Vlad Kozak <vk@stagem.com.ua>
 * @datetime: 15.08.2016 13:41
 */
namespace Stagem\ZfcPool\Service;

use Popov\ZfcCore\Service\DomainServiceAbstract;
use Stagem\ZfcPool\Model\Pool as Pool;

class PoolService extends DomainServiceAbstract
{
    protected $entity = Pool::class;

    /** @var Pool */
    protected $current;

    /**
     * @param Pool $current
     * @return $this
     */
    public function setCurrent(Pool $current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * @return Pool
     */
    public function getCurrent()
    {
        return $this->current;
    }

    public function save(Pool $pool)
    {
        $om = $this->getObjectManager();
        if (!$om->contains($pool)) {
            $om->persist($pool);
        }
        $om->flush();
    }
}