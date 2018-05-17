<?php
/**
 * Pool Plugin
 *
 * @category Stagem
 * @package Stagem_ZfcPool
 * @author Serhii Popov <popow.serhii@gmail.com>
 */

namespace Stagem\ZfcPool\Controller\Plugin;

use Stagem\ZfcPool\PoolHelper;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class PoolPlugin extends AbstractPlugin
{
    /** @var PoolHelper */
    protected $poolHelper;

    /**
     * @param PoolHelper $poolHelper
     */
    public function __construct(PoolHelper $poolHelper)
    {
        $this->poolHelper = $poolHelper;
    }

    public function __invoke()
    {
        return $this->poolHelper;
    }
}