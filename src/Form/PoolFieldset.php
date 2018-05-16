<?php
/**
 * @package Stagem_Pool
 * @author Vlad Kozak <vk@stagem.com.ua>
 * @datetime: 15.08.2016 13:41
 */
namespace Stagem\ZfcPool\Form;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Form\Fieldset;

class PoolFieldset extends Fieldset implements InputFilterProviderInterface, ObjectManagerAwareInterface
{
    use ProvidesObjectManager;

    public function init()
    {
        $this->setName('pool');
        $this->add(
            [
                'type' => 'Zend\Form\Element\Hidden',
                'name' => 'id',
            ]
        );
        $this->add(
            [
                'name' => 'name',
                'options' => [
                    'label' => 'Название',
                ],
                'attributes' => [
                    'id' => 'name',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Name ...',
                ],
            ]
        );
        $this->add(
            [
                'name' => 'address',
                'options' => [
                    'label' => 'Адреса',
                ],
                'attributes' => [
                    'id' => 'address',
                    'class' => 'form-control',
                    'placeholder' => 'Enter address...',
                ],
            ]
        );
        $this->add(
            [
                'name' => 'description',
                'options' => [
                    'label' => 'Описание',
                ],
                'attributes' => [
                    'id' => '',
                    'class' => 'form-control',
                    'placeholder' => 'Enter description ...',
                ],
            ]
        );
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [];
    }
}
