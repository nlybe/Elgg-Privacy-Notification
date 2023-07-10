<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

use PrivacyNotification\Elgg\Bootstrap;

require_once(dirname(__FILE__) . '/lib/events.php');

return [
    'plugin' => [
        'name' => 'Privacy Notification',
		'version' => '5.8',
		'dependencies' => [
			'datatables_api' => [
				'version' => '>5',
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
	'events' => [
		'login:forward' => [    // check after login if user has accept the privacy notification
			'user' => [
				'privacy_notification_acceptance_check' => [],
			],
		],
		'register' => [
			'user' => [ // save privacy notification acceptance on registration, if enabled
				'privacy_notification_accept_on_registration' => [],
			],
			'menu:user_hover' => [  // add option to users menu for set/unset acceptance
				'privacy_notification_user_menu_setup' => [],
			],
			'menu:admin_header' => [
				'privacy_notification_admin_menu' => ['priority' => 900],
			],
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
		'register/extend' => [
			'privacy_notification/registration' => [
				'priority' => 600
			],
		],
	],
	
];
