<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

use PrivacyNotification\PrivacyNotificationOptions;

$plugin = elgg_get_plugin_from_id(PrivacyNotificationOptions::PLUGIN_ID);

echo elgg_view_field([
    '#type' => 'text',
    'name' => 'params[identifiers]',
    'value' => $plugin->identifiers,
    '#label' => elgg_echo('privacy_notification:settings:identifiers'),
    '#help' => elgg_echo('privacy_notification:settings:identifiers:help'),
    'required' => true,
]);

echo elgg_view_field([
    '#type' => 'checkbox',
    'name' => 'params[enable_on_registration]',
    'switch' => true,
    'value' => 'yes',
    'checked' => ($plugin->enable_on_registration === 'yes'), 
    '#label' => elgg_echo('privacy_notification:settings:enable_on_registration'),
    '#help' => elgg_echo('privacy_notification:settings:enable_on_registration:help'),
]);

// echo elgg_view_field([
//     '#type' => 'checkbox',
//     'name' => 'params[enable_remove_account]',
//     'switch' => true,
//     'value' => 'yes',
//     'checked' => ($plugin->enable_remove_account === 'yes'), 
//     '#label' => elgg_echo('privacy_notification:settings:enable_remove_account'),
//     '#help' => elgg_echo('privacy_notification:settings:enable_remove_account:help'),
// ]);

echo elgg_view_field([
    '#type' => 'longtext',
    'name' => 'params[privacy_terms]',
    'value' => $plugin->privacy_terms,
    '#label' => elgg_echo('privacy_notification:settings:privacy_terms'),
    '#help' => elgg_echo('privacy_notification:settings:privacy_terms:help'),
    'required' => true,
]);

