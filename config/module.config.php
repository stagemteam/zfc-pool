<?php
/**
 * @package Stagem_Pool
 * @author Vlad Kozak <vk@stagem.com.ua>
 * @datetime: 15.08.2016 13:41
 */
namespace Stagem\ZfcPool;

return [
    'controllers' => [
        'aliases' => [
            'pool' => Controller\PoolController::class,
        ],
        'factories' => [
            Controller\PoolController::class => Controller\Factory\PoolControllerFactory::class,

        ],
    ],

	'service_manager' => [
		'aliases' => [
            'PoolService' => Service\PoolService::class,
            'PoolGrid' => Block\Grid\PoolGrid::class, // only for GridFactory
		],
        'invokables' => [
            Model\Pool::class => Model\Pool::class,
            Service\PoolService::class => Service\PoolService::class,
        ],
        'shared' => [
            Model\Pool::class => false,
        ],
	],

    'view_manager' => [
        'template_path_stack' => [
            __NAMESPACE__ => __DIR__ . '/../view',
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
    ],
];