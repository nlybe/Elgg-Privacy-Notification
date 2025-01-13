<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

use PrivacyNotification\PrivacyNotificationOptions;

if (!elgg_is_logged_in()) {
    
    $user_guid = get_input('user_guid');
    $invitecode = get_input('invitecode');

    $user = get_entity($user_guid?$user_guid:0);
    if (PrivacyNotificationOptions::privacyNotificationIsSet() && PrivacyNotificationOptions::hasAcceptPN($user)) {
        pn_forward(elgg_get_site_url());
    }

    if ($user && elgg_validate_invite_code($user->username, $invitecode)) {
        $vars['user_guid'] = $user_guid;
        $content = elgg_view_form('privacy_notification/acceptance', [], $vars);
    } else {
        // just show the privacy notifications
        $content = PrivacyNotificationOptions::getPrivacyNotificationText();
    }
} 
else if (
        PrivacyNotificationOptions::privacyNotificationIsSet() &&
        !PrivacyNotificationOptions::hasAcceptPN()) {

    $content = elgg_view_form('privacy_notification/acceptance', [], $vars);
} 
else {
    pn_forward(elgg_get_site_url());
}

$simple_view = get_input('s');

if (!$simple_view || !empty($simple_view)) {
    echo $content;
    return;
}

$params = [
    'title' => elgg_echo('privacy_notification:index:title'),
    'content' => $content,
    'filter' => false,
    'class' => 'elgg-river-layout',
];

$body = elgg_view_layout('privacy', $params);
echo elgg_view_page("", $body);
