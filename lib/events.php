<?php

/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 *
 * All events are here
 */

 use PrivacyNotification\PrivacyNotificationOptions;

/**
 * Check after login if user has accept the privacy notification. If not, redirect to notification page
 *  
 * @param \Elgg\Event $event
 * 
 * @return void
 */
function privacy_notification_acceptance_check(\Elgg\Event $event) {
    $return = $event->getValue();
    $user = $event->getEntityParam();

    if (!$user || PrivacyNotificationOptions::hasAcceptPN($user)) {
        return $return;
    }
    $return = elgg_normalize_url('privacy_notification');

    return $return;
}

/**
 * Change identifier if user hasn't accepted the privacy notification
 *  
 * @param \Elgg\Event $event
 * 
 * @return void
 */
function privacy_notification_acceptance_check_nav(\Elgg\Event $event) {
    $return = $event->getValue();
    
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
 * @param \Elgg\Event $event
 * 
 * @return void
 */
function privacy_notification_accept_on_registration(\Elgg\Event $event) {
    $return = $event->getValue();
    
    if (!PrivacyNotificationOptions::isEnabledOnRegistrattion()) {
        return $result;
    }    
    
    $user = $event->getParam('user');
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
 * @param \Elgg\Event $event
 * 
 * @return type
 */
function privacy_notification_user_menu_setup(\Elgg\Event $event) {
    $return = $event->getValue();
    
    $user = elgg_get_logged_in_user_entity();
    if (empty($user) || !$user->isAdmin()) {
        return $return;
    }

    $entity = $event->getEntityParam();    
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
 * Register menu in admin area
 * 
 * @param \Elgg\Event $event
 */ 
function privacy_notification_admin_menu(\Elgg\Event $event) {
    if (!elgg_in_context('admin')) {
        return null;
    }
    
    /* @var $return MenuItems */
    $result = $event->getValue();
    
    $result[] = \ElggMenuItem::factory([
        'name' => 'privacy_notification',
        'text' => elgg_echo('menu:page:header:privacy_notification_section'),
        'href' => false,
        'parent_name' => 'configure',
    ]);

    $result[] = \ElggMenuItem::factory([
        'name' => 'privacy_notification:pn_users_accepted',
        'href' => elgg_normalize_url("admin/privacy_notification/users?what=accepted"),
        'text' => elgg_echo("privacy_notification:admin:menu:users:accepted"),
        'parent_name' => 'privacy_notification',
    ]); 

    $result[] = \ElggMenuItem::factory([
        'name' => 'privacy_notification:pn_users_not_accepted',
        'href' => elgg_normalize_url("admin/privacy_notification/users?what=not_accepted"),
        'text' => elgg_echo("privacy_notification:admin:menu:users:not_accepted"),
        'parent_name' => 'privacy_notification',
    ]);
    
    return $result;
}


