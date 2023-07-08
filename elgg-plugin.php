<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

use PrivacyNotification\Elgg\Bootstrap;

require_once(dirname(__FILE__) . '/lib/hooks.php');

return [
    'plugin' => [
        'name' => 'Privacy Notification',
		'version' => '4.7',
		'dependencies' => [
			'datatables_api' => [
				'version' => '>4',
			]
		],
	],
    'bootstrap' => Bootstrap::class,
    'actions' => [
        'privacy_notification/acceptance' => ['access' => 'public'],
    ],
    'routes' => [
        'default:privacy_notification:users' => [
            'path' => '/privacy_notification/users/{type?}',
            'resource' => 'privacy_notification/users',
        ],
        'default:privacy_notification' => [
            'path' => '/privacy_notification/{index?}',
            'resource' => 'privacy_notification/index',
			'walled' => false,
        ],
    ],
    'widgets' => [],
    'views' => [
        'default' => [
            'privacy_notification/graphics/' => __DIR__ . '/graphics',           
        ],
    ],
    'upgrades' => [],
    'settings' => [
		'enable_on_registration' => 'no',
	],
	'view_extensions' => [
		'elgg.css' => [
			'privacy_notification/privacy_notification.css' => [],
		],
	],
	
];
