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
    $add = array();
    
    $add[] = 'privacy_notification';
    
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
