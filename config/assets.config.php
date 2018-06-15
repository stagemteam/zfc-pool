<?php
namespace Stagem\ZfcPool;

return [

    'routes' => [
        'admin(.*)' => [
            '@pool_css',
            '@pool_js',
        ],
    ],

    'modules' => [
        __NAMESPACE__ => [
            'root_path' => __DIR__ . '/../view/assets',
            'collections' => [
                'pool_css' => [
                    'assets' => [
                        'css/switcher.css',
                    ],
                    //'filters' => ['scss' => ['name' => \Assetic\Filter\ScssphpFilter::class]],
                    'options' => ['output' => 'pool.css'],
                ],
                'pool_js' => [
                    'assets' => [
                        'js/switcher.js',
                    ],
                ],

            ],
        ],
    ],
];