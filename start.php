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

    // register extra css
    elgg_extend_view('elgg.css', 'privacy_notification/privacy_notification.css');

    // Disabled as anonymized is deprecated for Elgg v3.x
    // elgg_register_page_handler('profile', 'privacy_notification_profile_page_handler'); 

    // Check after login if user has accept the privacy notification
    elgg_register_plugin_hook_handler('login:forward', 'user', 'privacy_notification_acceptance_check');

    // Save privacy notification acceptance on registration, if enabled
    elgg_register_plugin_hook_handler('register', 'user', 'privacy_notification_accept_on_registration');

    // register plugin hooks
    elgg_register_plugin_hook_handler("public_pages", "walled_garden", "privacy_notification_walled_garden_hook");

    // add option to users menu for set/unset acceptance
    elgg_register_plugin_hook_handler("register", "menu:user_hover", "privacy_notification_user_menu_setup");

    // replace views for anonymize users, if enabled
    elgg_register_plugin_hook_handler('view', 'user/elements/summary', 'privacy_notification_views_hook', 900);
    elgg_register_plugin_hook_handler('view', 'page/elements/by_line', 'privacy_notification_views_hook', 900);
    elgg_register_plugin_hook_handler('view', 'navigation/menu/user_hover', 'privacy_notification_views_hook', 900);
    elgg_register_plugin_hook_handler('view', 'river/elements/summary', 'privacy_notification_river_views_hook', 900);
    elgg_register_plugin_hook_handler('view', 'river/elements/body', 'privacy_notification_river_views_hook', 900);
    elgg_register_plugin_hook_handler('view', 'river/user/default/profileiconupdate', 'privacy_notification_river_views_hook', 900);
    elgg_register_plugin_hook_handler('view', 'river/user/default/profileupdate', 'privacy_notification_river_views_hook', 900);
    elgg_register_plugin_hook_handler('view', 'river/object/thewire/create', 'privacy_notification_river_views_hook', 900);
    elgg_register_plugin_hook_handler('view', 'river/object/discussion_reply/create', 'privacy_notification_river_views_hook', 900);
    elgg_register_plugin_hook_handler('view', 'object/page_top', 'privacy_notification_object_views_hook', 900);
    elgg_register_plugin_hook_handler('view', 'object/comment', 'privacy_notification_object_views_hook', 900);
    elgg_register_plugin_hook_handler('view', 'object/thewire', 'privacy_notification_object_views_hook', 900);

    // change avatar for users who haven't accepted the privacy, if anonymize users is enable in settings
    elgg_register_plugin_hook_handler('entity:icon:url', 'user', 'privacy_notification_icon_handler', 900);

//    // modify user menu list - Disabled as anonymized is deprecated for Elgg v3.x
//    elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'privacy_notification_user_menu_setup_clear', 6000);

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

}

/**
 * Disabled as anonymized is deprecated for Elgg v3.x
 * 
 * Custom Profile page handler, in order to check if have to anonymize user
 * 
 * @param array $page Array of URL segments passed by the page handling mechanism
 * @return bool
 */
// function privacy_notification_profile_page_handler($page) {

//     if (isset($page[0])) {
//         $username = $page[0];
//         $user = get_user_by_username($username);
//         elgg_set_page_owner_guid($user->guid);
//     } elseif (elgg_is_logged_in()) {
//         forward(elgg_get_logged_in_user_entity()->getURL());
//     }

//     // short circuit if invalid or banned username
//     if (!$user || ($user->isBanned() && !elgg_is_admin_logged_in())) {
//         elgg_error_response(elgg_echo('profile:notfound'));
//     }
    
//     if (PrivacyNotificationOptions::anonymizeUser($user) && !elgg_is_admin_logged_in()) {
//         elgg_error_response(elgg_echo('privacy_notification:anonymize:user:note'));
//     }

//     $action = NULL;
//     if (isset($page[1])) {
//         $action = $page[1];
//     }

//     if ($action == 'edit') {
//         // use the core profile edit page
//         echo elgg_view_resource('profile/edit');
//         return true;
//     }

//     echo elgg_view_resource('profile/view', [
//         'username' => $page[0],
//     ]);
//     return true;
// }
