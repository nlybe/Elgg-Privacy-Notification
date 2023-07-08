<?php

/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 *
 * All hooks are here
 */

 use PrivacyNotification\PrivacyNotificationOptions;

/**
 * Check after login if user has accept the privacy notification. If not, redirect to notification page
 *  
 * @param \Elgg\Hook $hook
 * 
 * @return void
 */
function privacy_notification_acceptance_check(\Elgg\Hook $hook) {
    $return = $hook->getValue();
    $user = $hook->getEntityParam();

    if (!$user || PrivacyNotificationOptions::hasAcceptPN($user)) {
        return $return;
    }
    $return = elgg_normalize_url('privacy_notification');

    return $return;
}

/**
 * Change identifier if user hasn't accepted the privacy notification
 *  
 * @param \Elgg\Hook $hook
 * 
 * @return void
 */
function privacy_notification_acceptance_check_nav(\Elgg\Hook $hook) {
    $return = $hook->getValue();
    
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
 * @param \Elgg\Hook $hook
 * 
 * @return void
 */
function privacy_notification_accept_on_registration(\Elgg\Hook $hook) {
    $return = $hook->getValue();
    
    if (!PrivacyNotificationOptions::isEnabledOnRegistrattion()) {
        return $result;
    }    
    
    $user = $hook->getParam('user');
    $acceptance = get_input("accept_privacy_notification");
    if (($user instanceof \ElggUser) && $acceptance == 'yes') {    
        $user->pn_acceptance = time();
        
        $user->pn_ip = filter_var( _elgg_services()->request->getClientIp(), FILTER_SANITIZE_STRING);
        $ua = PrivacyNotificationOptions::getBrowser();
        $user->pn_browser = $ua['name']." ".$ua['version']." (".$ua['platform'].")";
        $user->save();
    }
    
    return $result;
}

/**
 * Add option to users menu for set/unset acceptance
 * 
 * @param \Elgg\Hook $hook
 * 
 * @return type
 */
function privacy_notification_user_menu_setup(\Elgg\Hook $hook) {
    $return = $hook->getValue();
    
    $user = elgg_get_logged_in_user_entity();
    if (empty($user) || !$user->isAdmin()) {
        return $return;
    }

    $entity = $hook->getEntityParam();    
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


