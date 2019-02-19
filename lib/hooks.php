<?php

/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 *
 * All hooks are here
 */

/**
 * Check after login if user has accept the privacy notification. If not, redirect to notification page
 *  
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param bool   $return Whether to allow registration
 * @param array  $params Hook params
 * @return void
 */
function privacy_notification_acceptance_check($hook, $type, $return, $params) {

    if (!$params['user']) {
        return $return;
    }

    $user = $params['user'];
    if (PrivacyNotificationOptions::hasAcceptPN($user)) {
        return $return;
    }

    $return = elgg_normalize_url('privacy_notification');

    return $return;
}

/**
 * Change identifier if user hasn't accepted the privacy notification
 *  
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param bool   $return Whether to allow registration
 * @param array  $params Hook params
 * @return void
 */
function privacy_notification_acceptance_check_nav($hook, $type, $return, $params) {

    // do nothing if privacy notification hasn't been set
    if (!PrivacyNotificationOptions::privacyNotificationIsSet()) {
        return $return;
    }

    // do nothing for logged-out users
    $user = elgg_get_logged_in_user_entity();
    if (!$user) {
        return $return;
    }

    // do nothing if user has accept private notification
    if (PrivacyNotificationOptions::hasAcceptPN($user)) {
        return $return;
    }

    $return['identifier'] = 'privacy_notification';

    // unset segments so the default:privacy_notification route will be used, as set in elgg-plugin.php
    $return['segments'] = [];   
    
    return $return;
}
    
/**
 * Save privacy notification acceptance on registration, if enabled
 * 
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param bool   $result Whether to allow registration
 * @param array  $params Hook params
 * @return void
 */
function privacy_notification_accept_on_registration($hook, $type, $result, $params) {
    if (!PrivacyNotificationOptions::isEnabledOnRegistrattion()) {
        return $result;
    }    
    
    $user = $params['user'];
    $acceptance = get_input("accept_privacy_notification");
    if (($user instanceof \ElggUser) && $acceptance == 'yes') {    
        $user->pn_acceptance = time();
        
        $user->pn_ip = sanitize_string(_elgg_services()->request->getClientIp());
        $ua = PrivacyNotificationOptions::getBrowser();
        $user->pn_browser = $ua['name']." ".$ua['version']." (".$ua['platform'].")";
        $user->save();
    }
    
    return $result;
}

/**
 * Special Pages of the site which are required to be public
 *
 * @param string $hook
 * @param string $type
 * @param array $return_value
 * @param array $params
 * @return array
 */
function privacy_notification_walled_garden_hook($hook, $type, $return_value, $params) {
    $add = [];
    $add[] = 'privacy_notification';
    $add[] = 'privacy_notification/.*';
    
    if (is_array($return_value)) {
        $add = array_merge($add, $return_value);
    }

    return $add;
}

/**
 * Add option to users menu for set/unset acceptance
 * 
 * @param type $hook
 * @param type $type
 * @param array $return
 * @param type $params
 * @return type
 */
function privacy_notification_user_menu_setup($hook, $type, $return, $params) {
    
    $user = elgg_get_logged_in_user_entity();
    if (empty($user) || !$user->isAdmin()) {
        return $return;
    }

    if (empty($params) || !is_array($params)) {
        return $return;
    }
        
    $entity = elgg_extract("entity", $params);
    
    $text = elgg_echo("privacy_notification:accept:set");
    if ($entity->pn_acceptance) {
        $text = elgg_echo("privacy_notification:accept:unset");
    }

    $return[] = ElggMenuItem::factory(array(
        "name" => "privacy_acceptance",
        "text" => $text,
        "href" => elgg_normalize_url("action/privacy_notification/acceptance?user_guid={$entity->getGUID()}"),
        "section" => "admin",
        "is_action" => true,
    ));

    return $return;
}

/**
 * Replace various views
 * 
 * @param type $hook
 * @param type $type
 * @param type $return
 * @param type $params
 * @return type
 */
function privacy_notification_views_hook($hook, $type, $return, $params) {
    $check_hook = ($hook == 'view');
    if (!$check_hook) {
        return $return;
    }
 
    $anonymize_users = elgg_get_plugin_setting('anonymize_users', PrivacyNotificationOptions::PLUGIN_ID);
    if ($anonymize_users != PrivacyNotificationOptions::PARAM_YES) {
        return $return;
    }
    
    switch ($type) {
        case "user/elements/summary":
            return elgg_view('privacy_notification/user/elements/summary', $params['vars']);
            break;
        case "page/elements/by_line":
            return elgg_view('privacy_notification/page/elements/by_line', $params['vars']);
            break;
        case "navigation/menu/user_hover":
            return elgg_view('privacy_notification/navigation/menu/user_hover', $params['vars']);
            break;
            
        default:
            return $return;
    } 

    return $return;
}

/**
 * Replace river elements views
 * 
 * @param type $hook
 * @param type $type
 * @param type $return
 * @param type $params
 * @return type
 */
function privacy_notification_river_views_hook($hook, $type, $return, $params) {
    $check_hook = ($hook == 'view');
    if (!$check_hook) {
        return $return;
    }
 
    $anonymize_users = elgg_get_plugin_setting('anonymize_users', PrivacyNotificationOptions::PLUGIN_ID);
    if ($anonymize_users != PrivacyNotificationOptions::PARAM_YES) {
        return $return;
    }
    
    switch ($type) {
        case "river/elements/summary":
            return elgg_view('privacy_notification/river/elements/summary', $params['vars']);
            break;
        case "river/elements/body":
            return elgg_view('privacy_notification/river/elements/body', $params['vars']);
            break;
        case "river/user/default/profileiconupdate":
            return elgg_view('privacy_notification/river/user/default/profileiconupdate', $params['vars']);
            break;
        case "river/user/default/profileupdate":
            return elgg_view('privacy_notification/river/user/default/profileupdate', $params['vars']);
            break;
        case "river/object/thewire/create":
            return elgg_view('privacy_notification/river/object/thewire/create', $params['vars']);
            break;
        case "river/object/discussion_reply/create":
            return elgg_view('privacy_notification/river/object/discussion_reply/create', $params['vars']);
            break;
            
        default:
            return $return;
    } 

    return $return;
}

/**
 * Replace various object views
 * 
 * @param type $hook
 * @param type $type
 * @param type $return
 * @param type $params
 * @return type
 */
function privacy_notification_object_views_hook($hook, $type, $return, $params) {
    $check_hook = ($hook == 'view');
    if (!$check_hook) {
        return $return;
    }
 
    $anonymize_users = elgg_get_plugin_setting('anonymize_users', PrivacyNotificationOptions::PLUGIN_ID);
    if ($anonymize_users != PrivacyNotificationOptions::PARAM_YES) {
        return $return;
    }
    
    switch ($type) {
        case "object/page_top":
            return elgg_view('privacy_notification/object/page_top', $params['vars']);
            break;
        case "object/comment":
            return elgg_view('privacy_notification/object/comment', $params['vars']);
            break;
        case "object/thewire":
            return elgg_view('privacy_notification/object/thewire', $params['vars']);
            break;
            
        default:
            return $return;
    } 

    return $return;
}


/*
 * Change avatar for users who haven't accepted the privacy, if anonymize users is enable in settings
 * 
 * @param type $hook
 * @param type $type
 * @param type $url
 * @param type $params
 * @return type
 */
function privacy_notification_icon_handler($hook, $type, $url, $params) {
    if (elgg_is_admin_logged_in()) {
        return $url;
    }
    
    if (!PrivacyNotificationOptions::anonymizeUser($params['entity'])) {
        return $url;
    }

    $size = $params['size'];
    return elgg_get_simplecache_url("privacy_notification/graphics/default$size.png");
}

/**
 * Disabled as anonymized is deprecated for Elgg v3.x
 * 
 * @param type $hook_name
 * @param type $entity_type
 * @param type $return_value
 * @param type $params
 * @return type
 */
//function privacy_notification_user_menu_setup_clear($hook_name, $entity_type, $return, $params) {
//    
//    $entity = elgg_extract('entity', $params);
//    if (!($entity instanceof \ElggUser)) {
//        return $return;
//    }
//    
//    if (PrivacyNotificationOptions::anonymizeUser($entity)) {
//        return [];
//    }
//
//    return $return;
//}
