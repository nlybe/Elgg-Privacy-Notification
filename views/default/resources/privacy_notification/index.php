<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

$title = elgg_echo('privacy_notification:index:title');
$content = elgg_view_form('privacy_notification/acceptance', $form_vars, $vars);

$params = array(
    'title' => $title,
    'content' => $content,
    'sidebar' => $sidebar,
    'filter' => false,
    'class' => 'elgg-river-layout',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
