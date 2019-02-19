<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

$user_guid = (int) get_input("user_guid");

if (!empty($user_guid)) {
    $user = get_entity($user_guid);
    if ($user instanceof \ElggUser) {
        if ($user->pn_acceptance) {
            unset($user->pn_acceptance);
            unset($user->pn_ip);
            unset($user->pn_browser);
            $system_message_txt = elgg_echo("privacy_notification:action:acceptance:removed");
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
            $system_message_txt = elgg_echo("privacy_notification:action:acceptance:added", [$user->name]);
        }
    } 
    else {
        return elgg_error_response(elgg_echo('InvalidParameterException:NoEntityFound'));
    }
} 
else {
    return elgg_error_response(elgg_echo('InvalidParameterException:MissingParameter'));
}

$forward_url = elgg_get_site_url();
if (elgg_is_admin_logged_in()) {
    $forward_url = REFERER;
}

return elgg_ok_response('', $system_message_tx, $forward_url);
