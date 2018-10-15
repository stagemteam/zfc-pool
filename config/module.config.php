<?php
/**
 * @package Stagem_Pool
 * @author Vlad Kozak <vk@stagem.com.ua>
 * @datetime: 15.08.2016 13:41
 */

namespace Stagem\ZfcPool;

use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
    'assetic_configuration' => require 'assets.config.php',

    'dependencies' => [
        'aliases' => [
            'PoolService' => Service\PoolService::class,
            'PoolGrid' => Block\Grid\PoolGrid::class, // only for GridFactory
        ],
        'factories' => [
            Service\PoolService::class => Service\Factory\PoolServiceFactory::class,
        ],
    ],

    'controllers' => [
        'aliases' => [
            'pool' => Controller\PoolController::class,
        ],
        'factories' => [
            Controller\PoolController::class => Controller\Factory\PoolControllerFactory::class,
        ],
    ],

    'controller_plugins' => [
        'aliases' => [
            'pool' => Controller\Plugin\PoolPlugin::class,
        ],
        'factories' => [
            Controller\Plugin\PoolPlugin::class => ReflectionBasedAbstractFactory::class,
        ]
    ],

    'view_helpers' => [
        'aliases' => [
            'pool' => View\Helper\PoolHelper::class,
        ],
        'factories' => [
            View\Helper\PoolHelper::class => ReflectionBasedAbstractFactory::class,
        ]
    ],

    'view_manager' => [
        /*'template_path_stack' => [
            __NAMESPACE__ => __DIR__ . '/../view',
        ],*/
        'prefix_template_path_stack' => [
            'pool::' => __DIR__ . '/../view',
        ],
    ],

    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Model']
            ],
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    __NAMESPACE__ . '\Model' => __NAMESPACE__ . '_driver'
                ]
            ]
        ],

        'configuration' => [
            'orm_default' => [
                'filters' => [
                    'pool_filter' => Model\Filter\PoolFilter::class,
                ],
            ],
        ],
    ],
];