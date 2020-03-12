<?php

use Pagekit\Search\Plugin\SearchContentPlugin;
use Pagekit\Search\Plugin\SearchPagePlugin;
use Pagekit\Search\Plugin\SearchBlogPlugin;
use Pagekit\Search\Plugin\SearchNewsPlugin;

return [

    'name' => 'search',

    'type' => 'extension',

    'autoload' => [

		'Pagekit\\Search\\' => 'src'

    ],

	'nodes' => [

        'search' => [
			'name' => '@search',
            'label' => 'Search',
            'controller' => 'Pagekit\\Search\\Controller\\SiteController',
            'protected' => true,
            'frontpage' => true
        ]
    ],

	'routes' => [

        '/search' => [
            'name' => '@search',
            'controller' => [
			'Pagekit\\Search\\Controller\\SearchController'
			]
        ],
		'/api/search' => [
            'name' => '@search/api',
            'controller' => [
                'Pagekit\\Search\\Controller\\StatisticsApiController',
            ]
        ]
    ],
	
	'widgets' => [

        'widgets/search.php'

    ],
	
	
	'resources' => [

		'search:' => '',
		'views:search' => 'views'

	],

	
	'config' => [

        'defaults' => [

			'limit_search_result'    => 50,
            'result_per_page'        => 10,
            'data_creation'          => true,
            'use_areas_search'       => true, 
            'markdown_enabled'       => true,
			'show_pages_counter'	 => true,
			'show_posted_in'	  	 => true,
			'title'            	  	 => 'Search Title',
            'show_title'             => true

        ],
		'advanced' => [

            'statistics_enabled'       => true,
        ],
    ],

    'menu' => [

        'search' => [
            'label'  => 'Search',
            'icon'   => 'search:icon.svg',
            'url'    => '@search/statistics',
            'active' => '@search/statistics*',
            'access' => 'search: see search',
            'priority' => 116
        ],
		'search: statistics' => [
            'parent' => 'search',
            'label' => 'Statistics',
            'icon' => 'search:icon.svg',
            'url' => '@search/statistics',
            'access' => 'search: manage search'
        ],
        'search: settings' => [
            'parent' => 'search',
            'label' => 'Settings',
            'url' => '@search/settings',
            'access' => 'system: manage settings'
        ]
    ],

    'permissions' => [

        'search: see search' => [
            'title' => 'See search result'
        ],
		'search: manage settings' => [
            'title' => 'Manage settings'
        ],

    ],
	
	'settings' => '@search/settings',
	 
	'events' => [

        'boot' => function ($event, $app) {
            $app->subscribe(
                //new SearchContentPlugin,
                new SearchPagePlugin,
                new SearchBlogPlugin,
                new SearchNewsPlugin
                
            );
        },
		'view.scripts' => function ($event, $scripts) use ($app) {
            //admin/site/page/link
            // $scripts->register('link-search', 'search:app/bundle/link-search.js', '~panel-link');
            //frontend
            $scripts->register('uikit-search', 'app/assets/uikit/js/components/search.min.js', 'uikit');
            $scripts->register('uikit-autocomplete', 'app/assets/uikit/js/components/autocomplete.min.js', 'uikit');
        }

    ]

];