<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

if (!elgg_is_logged_in()) {
    $user_guid = get_input('user_guid');
    $invitecode = get_input('invitecode');

    $user = get_entity($user_guid);
    if (PrivacyNotificationOptions::privacyNotificationIsSet() && PrivacyNotificationOptions::hasAcceptPN($user)) {
        forward(elgg_get_site_url());
    }

    if (elgg_validate_invite_code($user->username, $invitecode)) {
        $vars['user_guid'] = $user_guid;
        $content = elgg_view_form('privacy_notification/acceptance', $form_vars, $vars);
        $page_type = 'content';
    } else {
        // just show the privacy notifications
        $content = PrivacyNotificationOptions::getPrivacyNotificationText();
        $page_type = 'one_column';
    }
} 
else if (
        PrivacyNotificationOptions::privacyNotificationIsSet() &&
        !PrivacyNotificationOptions::hasAcceptPN()) {

    $content = elgg_view_form('privacy_notification/acceptance', $form_vars, $vars);
    $page_type = 'content';
} 
else {
    forward(elgg_get_site_url());
}


$params = array( 
    'title' => elgg_echo('privacy_notification:index:title'),
    'content' => $content,
    // 'sidebar' => $sidebar,
    'filter' => false,
    'class' => 'elgg-river-layout',
);

$body = elgg_view_layout($page_type, $params);

echo elgg_view_page($title, $body);
