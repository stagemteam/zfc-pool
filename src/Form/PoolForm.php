<?php
/**
 * @package Stagem_Pool
 * @author Vlad Kozak <vk@stagem.com.ua>
 * @datetime: 15.08.2016 13:41
 */
namespace Stagem\ZfcPool\Form;

use Zend\Form\Form;

class PoolForm extends Form
{
    protected $objectManager;

    public function init()
    {
        $this->setName('pool');
        $this->add(
            [
                'name' => 'pool',
                'type' => 'Stagem\Pool\Form\PoolFieldset',
                'options' => [
                    'use_as_base_fieldset' => true,
                ],
            ]
        );
        $this->add(
            [
                'name' => 'submit',
                'attributes' => [
                    'type' => 'submit',
                    'value' => 'Send',
                    'class' => 'btn btn-primary',
                ],
            ]
        );
    }
}