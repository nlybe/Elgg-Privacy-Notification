<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

$plugin = elgg_get_plugin_from_id(PrivacyNotificationOptions::PLUGIN_ID);

$potential_yes_no = array(
    elgg_echo('privacy_notification:settings:yes') => PrivacyNotificationOptions::PARAM_YES,
    elgg_echo('privacy_notification:settings:no') => PrivacyNotificationOptions::PARAM_NO,
);

echo elgg_format_element('div', [], elgg_view_input('text', array(
    'name' => 'params[identifiers]',
    'value' => $plugin->identifiers,
    'label' => elgg_echo('privacy_notification:settings:identifiers'),
    'help' => elgg_echo('privacy_notification:settings:identifiers:help'),
    'required' => true,
)));

echo elgg_format_element('div', [], elgg_view_input('radio', array(
    'name' => 'params[enable_on_registration]',
    'value' => ($plugin->enable_on_registration?$plugin->enable_on_registration:PrivacyNotificationOptions::PARAM_NO), 
    'options' => $potential_yes_no, 
    'align' => 'horizontal',
    'label' => elgg_echo('privacy_notification:settings:enable_on_registration'),
    'help' => elgg_echo('privacy_notification:settings:enable_on_registration:help'),
)));

echo elgg_format_element('div', [], elgg_view_input('radio', array(
    'name' => 'params[enable_remove_account]',
    'value' => ($plugin->enable_remove_account?$plugin->enable_remove_account:PrivacyNotificationOptions::PARAM_NO), 
    'options' => $potential_yes_no, 
    'align' => 'horizontal',
    'label' => elgg_echo('privacy_notification:settings:enable_remove_account'),
    'help' => elgg_echo('privacy_notification:settings:enable_remove_account:help'),
)));

echo elgg_format_element('div', [], elgg_view_input('longtext', array(
    'name' => 'params[privacy_terms]',
    'value' => $plugin->privacy_terms,
    'label' => elgg_echo('privacy_notification:settings:privacy_terms'),
    'help' => elgg_echo('privacy_notification:settings:privacy_terms:help'),
    'required' => true,
)));

