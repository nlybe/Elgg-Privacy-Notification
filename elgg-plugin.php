<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

return [
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
		'anonymize_users' => 'no',
	],
	
];
