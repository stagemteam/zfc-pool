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

namespace Stagem\ZfcPool\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Stagem\ZfcPool\PoolHelper as ZfcPoolHelper;

class PoolHelper extends AbstractHelper
{
    /**
     * @var ZfcPoolHelper
     */
    protected $poolHelper;

    public function __construct(ZfcPoolHelper $poolHelper)
    {
        $this->poolHelper = $poolHelper;
    }

    public function getPoolHelper()
    {
        return $this->poolHelper;
    }

    public function __invoke()
    {
        $params = func_get_args();

        return call_user_func_array($this->getPoolHelper(), $params);
    }
}