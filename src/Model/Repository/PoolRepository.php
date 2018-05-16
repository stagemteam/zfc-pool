<?php
/**
 * @package Stagem_Pool
 * @author Vlad Kozak <vk@stagem.com.ua>
 * @datetime: 15.08.2016 13:41
 */
namespace Stagem\ZfcPool\Model\Repository;

class PoolRepository extends \Doctrine\ORM\EntityRepository
{
    protected $_alias = 'pool';

    public function getPools()
    {
        $qb = $this->createQueryBuilder($this->_alias);

        return $qb;
    }
}