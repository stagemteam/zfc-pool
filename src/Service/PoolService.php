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

    public function save(Pool $pool)
    {
        $om = $this->getObjectManager();
        if (!$om->contains($pool)) {
            $om->persist($pool);
        }
        $om->flush();
    }
}