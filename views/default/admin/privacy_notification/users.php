<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

// restrict pages only to admins
admin_gatekeeper();

$type = get_input('what');
if (!$type) {
    echo elgg_echo('admin:privacy_notification:etype:missing');
    return;
}

// set title
$title = elgg_echo("admin:privacy_notification:etype:{$type}");

$dt_options = [
    'action' => "privacy_notification/users/{$type}",
];

$dt_options['headers'] = [ 
    ['name' => 'id', 'label' => elgg_echo('privacy_notification:admin:users:table:header:id')],
    ['name' => 'name', 'label' => elgg_echo('privacy_notification:admin:users:table:header:name')],
    ['name' => 'username', 'label' => elgg_echo('privacy_notification:admin:users:table:header:username')],
    ['name' => 'email', 'label' => elgg_echo('privacy_notification:admin:users:table:header:email')],
];  

if ($type == 'accepted') { 
    $dt_options['headers'][] = ['name' => 'accepted', 'label' => elgg_echo('privacy_notification:admin:users:table:header:accepted')];
    $dt_options['headers'][] = ['name' => 'ip', 'label' => elgg_echo('privacy_notification:admin:users:table:header:ip')];
    $dt_options['headers'][] = ['name' => 'browser', 'label' => elgg_echo('privacy_notification:admin:users:table:header:browser')];
}
else {
    $dt_options['headers'][] = ['name' => 'invite_url', 'label' => elgg_echo('privacy_notification:admin:users:table:header:invite_url')];
}

$dt_options['headers'][] = ['name' => 'actions', 'label' => elgg_echo('privacy_notification:admin:users:table:header:actions')];

$content = elgg_view('datatables_api/dtapi_ajax', $dt_options);

echo elgg_format_element('div', ['style' => 'margin: 0 0 10px;'], elgg_view_title($title));
echo elgg_format_element('div', [], $content);
