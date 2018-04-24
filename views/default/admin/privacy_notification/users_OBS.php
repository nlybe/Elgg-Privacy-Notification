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

$options = array(
    'type' => 'user',
    'limit' => 0,
);

if ($type == 'accepted') { 
    $options['metadata_name_value_pairs'] = array(
        array('name' => 'pn_acceptance','value' => 0, 'operand' => '>'),
    );
}
else {
    $db_prefix = elgg_get_config('dbprefix');
    $options['wheres'][] = "NOT EXISTS ( SELECT 1 FROM {$db_prefix}metadata md, {$db_prefix}metastrings ms WHERE md.entity_guid = e.guid AND md.name_id = ms.id AND ms.string = 'pn_acceptance')";
}
$entities = elgg_get_entities_from_metadata($options);

if ($entities) {
    if (elgg_is_active_plugin('datatables_api')) {
        
        $dt_options = [];
        $dt_options['dt_titles'] = [
            elgg_echo('privacy_notification:admin:users:table:header:id'),
            elgg_echo('privacy_notification:admin:users:table:header:name'),
            elgg_echo('privacy_notification:admin:users:table:header:username'),
            elgg_echo('privacy_notification:admin:users:table:header:email'),
        ];        
        if ($type == 'accepted') { 
            $dt_options['dt_titles'][] = elgg_echo('privacy_notification:admin:users:table:header:accepted');
            $dt_options['dt_titles'][] = elgg_echo('privacy_notification:admin:users:table:header:ip');
            $dt_options['dt_titles'][] = elgg_echo('privacy_notification:admin:users:table:header:browser');
        }
        else {
            $dt_options['dt_titles'][] = elgg_echo('privacy_notification:admin:users:table:header:invite_url');
        }        
        $dt_options['dt_titles'][] = elgg_echo('privacy_notification:admin:users:table:header:actions');

        
//        $dt_data = [];
//        foreach ($entities as $e) {
//            $dt_data_tmp = [];
//
//            $owner = get_entity($e->owner_guid);
//            $container = get_entity($e->container_guid);
//
//            // datatable 
//            $dt_data_tmp['guid'] = $e->getGUID();
//            $dt_data_tmp['name'] = elgg_view('output/url', array(
//                'href' => $e->getURL(),
//                'text' => $e->name,
//                'title' => elgg_echo('privacy_notification:admin:users:view_user'),
//                'is_trusted' => true,
//            ));
//            $dt_data_tmp['username'] = elgg_view('output/url', array(
//                'href' => $e->getURL(),
//                'text' => $e->username,
//                'title' => elgg_echo('privacy_notification:admin:users:view_user'),
//                'is_trusted' => true,
//            ));
//            $dt_data_tmp['email'] = elgg_view('output/email', array(
//                'value' => $e->email,
//            ));
//            
//            if ($type == 'accepted') { 
//                $dt_data_tmp['accepted'] = $e->pn_acceptance?elgg_get_friendly_time($e->pn_acceptance):'';
//                $dt_data_tmp['ip'] = $e->pn_ip;
//                $dt_data_tmp['browser'] = $e->pn_browser;
//            }
//            else {            
//                $invite_url = PrivacyNotificationOptions::getInviteUrl($e);
//                $dt_data_tmp['invite_url'] = elgg_view('output/url', [
//                    "name" => "invite_{$e->getGUID()}",
//                    "text" => $invite_url,
//                    "href" => $invite_url,
//                    'is_trusted' => true,
//                ]);
//            }
//            
//            $text = elgg_echo("privacy_notification:accept:set");
//            if ($e->pn_acceptance) {
//                $text = elgg_echo("privacy_notification:accept:unset");
//            }
//            $dt_data_tmp['actions'] = elgg_view('output/url', [
//                "name" => "privacy_acceptance_{$e->getGUID()}",
//                "text" => $text,
//                "href" => elgg_normalize_url("action/privacy_notification/acceptance?user_guid={$e->getGUID()}"),
//                "is_action" => true,
//                "is_trusted" => true,
//            ]);
//            array_push($dt_data, $dt_data_tmp);        
//        }

        $dt_options['dt_data'] = $dt_data;

        $content = elgg_view('datatables_api/datatables_api', $dt_options);
    }
    else {
        $content = elgg_view_entity_list($entities, [
            'count' => count($entities),
            'full_view' => false,
            'pagination' => true,
        ]);
    }
}  
else {
    $content = elgg_format_element('div', [], elgg_echo('admin:privacy_notification:no_results'));
}

echo elgg_format_element('div', ['style' => 'margin: 15px 0;'], elgg_view_title($title));
echo elgg_format_element('div', [], $content);

// unset variables
unset($entities);
unset($dt_data);
