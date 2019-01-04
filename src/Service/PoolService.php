<?php

namespace Stagem\ZfcPool\Service;

use Popov\ZfcCore\Service\DomainServiceAbstract;
use Stagem\ZfcPool\Model\PoolInterface;

class PoolService extends DomainServiceAbstract
{
    /**
     * Admin pool must be integer such as it is saved to configuration table.
     *
     * @var string For compatibility with url parameter
     */
    const POOL_ADMIN = '0';

    const SESSION_KEY = 'pool_id';

    /**
     * @var PoolInterface
     */
    #protected $adminPool;

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

    /**
     * @todo Remove to getAvailablePools or simply getPools
     * @return object[]
     */
    public function getActivePools()
    {
        // @todo Add isEnable property to interface and filter by this value
        return $this->getRepository()->findBy([]);
    }

    public static function getAdminPool($class)
    {
        static $adminPool;

        if (!$adminPool) {
            $adminPool = ($class === PoolInterface::class)
                ? static::createAnonymousPool()
                : new $class();

            $adminPool->setId(PoolService::POOL_ADMIN)
                ->setName('Default Configuration');
        }

        return $adminPool;
    }

    /**
     * Create anonymous pool for projects which need no Strategy
     *
     * @return PoolInterface
     */
    protected static function createAnonymousPool()
    {
        $pool = new class() implements PoolInterface
        {
            protected $id;

            protected $name;

            public function setId($id)
            {
                $this->id = $id;

                return $this;
            }

            public function getId()
            {
                return $this->id;
            }

            public function setName($name)
            {
                $this->name = $name;

                return $this;
            }

            public function getName()
            {
                return $this->name;
            }
        };

        return $pool;
    }
}