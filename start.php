<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */
 
require_once(dirname(__FILE__) . '/lib/hooks.php');

elgg_register_event_handler('init', 'system', 'privacy_notification_init');

// Check if registered user have accept the privacy notification, while navigating
$identifiers = PrivacyNotificationOptions::getSiteIdentifiers();
foreach ($identifiers as $identifier) {
    elgg_register_plugin_hook_handler('route:rewrite', $identifier, 'privacy_notification_acceptance_check_nav'); 
}

/**
 * privacy_notification plugin initialization functions.
 */
function privacy_notification_init() {
 	
    // register a library of helper functions
    elgg_register_library('elgg:privacy_notification', elgg_get_plugins_path() . 'privacy_notification/lib/privacy_notification.php');
    
    // register extra css
    elgg_extend_view('elgg.css', 'privacy_notification/privacy_notification.css');
    
    // page handler for privacy_notification
    elgg_register_page_handler('privacy_notification', 'privacy_notification_river_page_handler'); 
    
    // Check after login if user has accept the privacy notification
    elgg_register_plugin_hook_handler('login:forward', 'user', 'privacy_notification_acceptance_check'); 
    
    // Save privacy notification acceptance on registration, if enabled
    elgg_register_plugin_hook_handler('register', 'user', 'privacy_notification_accept_on_registration');
    
    // register plugin hooks
    elgg_register_plugin_hook_handler("public_pages", "walled_garden", "privacy_notification_walled_garden_hook");
    
    // add option to users menu for set/unset acceptance
    elgg_register_plugin_hook_handler("register", "menu:user_hover", "privacy_notification_user_menu_setup");
    
    elgg_extend_view('register/extend', 'privacy_notification/registration');
    
    // register menu in admin area
    if (elgg_get_context() == 'admin') {        
        elgg_register_menu_item('page', array(
            'name' => "pn_users_accepted",
            'href' => elgg_normalize_url("admin/privacy_notification/users?what=accepted"),
            'text' => elgg_echo("privacy_notification:admin:menu:users:accepted"),
            'context' => 'admin',
            'section' => 'privacy_notification_section',
        ));
        elgg_register_menu_item('page', array(
            'name' => "pn_users_not_accepted",
            'href' => elgg_normalize_url("admin/privacy_notification/users?what=not_accepted"),
            'text' => elgg_echo("privacy_notification:admin:menu:users:not_accepted"),
            'context' => 'admin',
            'section' => 'privacy_notification_section',
        ));        
    }
    
    // Register actions
    $action_path = elgg_get_plugins_path() . 'privacy_notification/actions/privacy_notification';
    elgg_register_action('privacy_notification/acceptance', "$action_path/acceptance.php", 'public'); 
}

/**
 * New river page handler: replace the core river
 * 
 * @param type $page
 * @return boolean
 */
function privacy_notification_river_page_handler($page) {
    elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

    // make a URL segment available in page handler script
    $page_type = elgg_extract(0, $page, 'index');
    $page_type = preg_replace('[\W]', '', $page_type);

    $vars['page_type'] = $page_type;

    
    if (!elgg_is_logged_in()) {
        $user_guid = get_input('user_guid');
        $invitecode = get_input('invitecode');
        
        $user = get_entity($user_guid);
        if (PrivacyNotificationOptions::privacyNotificationIsSet() && PrivacyNotificationOptions::hasAcceptPN($user)) {
            forward(elgg_get_site_url());
            return true;
        }
        
        if (elgg_validate_invite_code($user->username,$invitecode)) {
            $vars['user_guid'] = $user_guid;
            echo elgg_view_resource("privacy_notification/index", $vars);
        }
        else {
            // just show the privacy notifications
            echo elgg_view_resource("privacy_notification/terms", $vars);
        }
    }
    else if (
            PrivacyNotificationOptions::privacyNotificationIsSet() && 
            !PrivacyNotificationOptions::hasAcceptPN()) {
        
        echo elgg_view_resource("privacy_notification/index", $vars);
    }
    else {
        forward(elgg_get_site_url());
    }
    return true;
}

