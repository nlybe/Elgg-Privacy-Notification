<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

$title = elgg_echo('privacy_notification:index:title');
$content = PrivacyNotificationOptions::getPrivacyNotificationText();

$params = array(
    'title' => $title,
    'content' => $content,
    'sidebar' => $sidebar,
    'filter' => false,
);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page($title, $body);
