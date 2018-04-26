<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

$search = get_input('search');
$type = elgg_extract('type', $vars, '');
$db_prefix = elgg_get_config('dbprefix');

$options = array(
    'type' => 'user',
    'count' => true,
    'limit' => 0,
);

$options["joins"] = [];
$options["wheres"] = [];

if ($type == 'accepted') { 
    $options['metadata_name_value_pairs'] = array(
        array('name' => 'pn_acceptance','value' => 0, 'operand' => '>'),
    );
}
else {
    $options['wheres'][] = "NOT EXISTS ( SELECT 1 FROM {$db_prefix}metadata md, {$db_prefix}metastrings ms WHERE md.entity_guid = e.guid AND md.name_id = ms.id AND ms.string = 'pn_acceptance')";
}

if ($search && !empty($search['value'])) {
    $query = sanitise_string($search['value']);
		
    array_push($options["joins"], "JOIN {$db_prefix}users_entity ge ON e.guid = ge.guid");
    array_push($options["wheres"], "(ge.name LIKE '%$query%' OR ge.username LIKE '%$query%' OR ge.email LIKE '%$query%')");
}

$totalEntries = elgg_get_entities_from_metadata($options);

$options['count'] = false;
$options['limit'] = max ((int) get_input("length", elgg_get_config('default_limit')), 0);
$options['offset'] = sanitise_int(get_input ("start", 0), false);
$entities = elgg_get_entities_from_metadata($options);

$dt_data = [];
if ($entities) {    
    foreach ($entities as $e) {
        $dt_data_tmp = [];

        // datatable 
        $dt_data_tmp['id'] = $e->getGUID();
        $dt_data_tmp['name'] = elgg_view('output/url', array(
            'href' => $e->getURL(),
            'text' => $e->name,
            'title' => elgg_echo('privacy_notification:admin:users:view_user'),
            'is_trusted' => true,
        ));
        $dt_data_tmp['username'] = elgg_view('output/url', array(
            'href' => $e->getURL(),
            'text' => $e->username,
            'title' => elgg_echo('privacy_notification:admin:users:view_user'),
            'is_trusted' => true,
        ));
        $dt_data_tmp['email'] = elgg_view('output/email', array(
            'value' => $e->email,
        ));
        
        if ($type == 'accepted') { 
            $dt_data_tmp['accepted'] = $e->pn_acceptance?elgg_get_friendly_time($e->pn_acceptance):'';
            $dt_data_tmp['ip'] = $e->pn_ip;
            $dt_data_tmp['browser'] = $e->pn_browser;
        }
        else {            
            $invite_url = PrivacyNotificationOptions::getInviteUrl($e);
            $dt_data_tmp['invite_url'] = elgg_view('output/url', [
                "name" => "invite_{$e->getGUID()}",
                "text" => $invite_url,
                "href" => $invite_url,
                'is_trusted' => true,
            ]);
        }

        $text = elgg_echo("privacy_notification:accept:set");
        if ($e->pn_acceptance) {
            $text = elgg_echo("privacy_notification:accept:unset");
        }
        $dt_data_tmp['actions'] = elgg_view('output/url', [
            "name" => "privacy_acceptance_{$e->getGUID()}",
            "text" => $text,
            "href" => elgg_normalize_url("action/privacy_notification/acceptance?user_guid={$e->getGUID()}"),
            "is_action" => true,
            "is_trusted" => true,
        ]);
            
        array_push($dt_data, $dt_data_tmp);        
    }
} 

$total_rows = count($entities);
$draw = get_input('draw');
$result = [
    'draw' => isset($draw)?intval($draw):1,
    'recordsTotal' => $totalEntries,
    'recordsFiltered' => $totalEntries,
    'data' => $dt_data,
];

// release variables
unset($entities);

echo json_encode($result);
exit;
