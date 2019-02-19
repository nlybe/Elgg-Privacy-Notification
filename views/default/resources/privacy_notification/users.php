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
    $options['wheres'][] = "NOT EXISTS ( SELECT 1 FROM {$db_prefix}metadata md WHERE md.entity_guid = e.guid AND md.name = 'pn_acceptance')";
}

if ($search && !empty($search['value'])) {
    $query = sanitise_string($search['value']);

    $options["wheres"] = [
        function(\Elgg\Database\QueryBuilder $qb, $alias) use ($query, $type) {
            $ands = [];

            if ($query && !empty($query)) {
                $joined_alias = $qb->joinMetadataTable($alias, 'guid', 'name', 'inner', 'alias_1');
                $ands[] = $qb->compare("$joined_alias.value", 'like', "%$query%", ELGG_VALUE_STRING);

                $joined_alias = $qb->joinMetadataTable($alias, 'guid', 'username', 'inner', 'alias_2');
                $ands[] = $qb->compare("$joined_alias.value", 'like', "%$query%", ELGG_VALUE_STRING);
            
                $joined_alias = $qb->joinMetadataTable($alias, 'guid', 'email', 'inner', 'alias_3');
                $ands[] = $qb->compare("$joined_alias.value", 'like', "%$query%", ELGG_VALUE_STRING);
            }

            return $qb->merge($ands, 'OR');
        }
    ];

    if ($type != 'accepted') {
        $options['wheres'][] = "NOT EXISTS ( SELECT 1 FROM {$db_prefix}metadata md WHERE md.entity_guid = e.guid AND md.name = 'pn_acceptance')";
    }
}



$totalEntries = elgg_get_entities($options);

$options['count'] = false;
$options['limit'] = max ((int) get_input("length", elgg_get_config('default_limit')), 0);
$options['offset'] = sanitise_int(get_input ("start", 0), false);
$entities = elgg_get_entities($options);

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
