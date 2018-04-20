<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

//$cuser = elgg_get_logged_in_user_entity();
//if (!$cuser) {
//    register_error(elgg_echo("privacy_notification:action:acceptance:error:logged_out"));
//}
    
$user_guid = (int) get_input("user_guid");

if (!empty($user_guid)) {
    $user = get_entity($user_guid);
    if ($user instanceof \ElggUser) {
        if ($user->pn_acceptance) {
            unset($user->pn_acceptance);
            unset($user->pn_ip);
            unset($user->pn_browser);
            system_message(elgg_echo("privacy_notification:action:acceptance:removed"));
        } 
        else {
            $user->pn_acceptance = time();
            if (!elgg_is_admin_logged_in()) {
                // record ip and browser only if not set by admin
                $user->pn_ip = sanitize_string(_elgg_services()->request->getClientIp());

                $ua = PrivacyNotificationOptions::getBrowser();
                $user->pn_browser = $ua['name']." ".$ua['version']." (".$ua['platform'].")";
            }
            $user->save();
            system_message(elgg_echo("privacy_notification:action:acceptance:added", [$user->name]));
        }
    } 
    else {
        register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
    }
} 
else {
    register_error(elgg_echo("InvalidParameterException:MissingParameter"));
}

if (elgg_is_admin_logged_in()) {
    forward(REFERER);
}


forward(elgg_get_site_url());
