<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

use PrivacyNotification\PrivacyNotificationOptions;

if (!PrivacyNotificationOptions::isEnabledOnRegistrattion()) {
    return;
}

$pn = elgg_view('output/url', array(
    'href' => elgg_normalize_url("privacy_notification"),
    'target' => '_blank',
    'text' => elgg_echo('privacy_notification:registration:terms'),
    'title' => elgg_echo('privacy_notification:registration:terms'),
));

$checkbox = elgg_view('input/checkbox', [
    'id' => 'register-accept_privacy_notification',
    'name' => 'accept_privacy_notification',
    'value' => 'yes',
    'label' => elgg_echo('privacy_notification:registration:label', [$pn]),
    'default' => false,
    'required' => true,
]);

echo elgg_format_element('div', [], $checkbox);
