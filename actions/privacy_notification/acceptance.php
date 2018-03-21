<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

$cuser = elgg_get_logged_in_user_entity();
if (!$cuser) {
    register_error(elgg_echo("privacy_notification:action:acceptance:error:logged_out"));
}
    
$user_guid = (int) get_input("user_guid");

if (!empty($user_guid)) {
    if ($user = get_user($user_guid)) {
        if ($user->pn_acceptance) {
            unset($user->pn_acceptance);
            unset($user->pn_ip);
            unset($user->pn_browser);
            system_message(elgg_echo("privacy_notification:action:acceptance:removed"));
        } else {
            $user->pn_acceptance = time();
            if ($user_guid == $cuser->getGUID()) {
                $user->pn_ip = sanitize_string(_elgg_services()->request->getClientIp());

                $ua = PrivacyNotificationOptions::getBrowser();
                $user->pn_browser = $ua['name']." ".$ua['version']." (".$ua['platform'].")";
            }
            $user->save();
            system_message(elgg_echo("privacy_notification:action:acceptance:added"));
        }
    } 
    else {
        register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
    }
} 
else {
    register_error(elgg_echo("InvalidParameterException:MissingParameter"));
}

forward(REFERER);