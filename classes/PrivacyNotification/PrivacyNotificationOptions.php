<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

namespace PrivacyNotification;

class PrivacyNotificationOptions {

    const PLUGIN_ID = 'privacy_notification';    // current plugin ID
    const PARAM_YES = 'yes';                    // general purpose value for yes
    const PARAM_NO = 'no';                      // general purpose value for no
        
    /**
     * Get an array with site url identifiers, according the site pages
     * Read more about Elgg URL identifiers at https://learn.elgg.org/en/4.3/guides/routing.html
     * 
     * @return array
     */
    Public Static function getSiteIdentifiers() {
        $identifiers = elgg_get_plugin_setting('identifiers', self::PLUGIN_ID); 
        if (!$identifiers) {
            return [];
        }
 
        $field_values = explode(",", $identifiers);
                
        return array_map('trim',$field_values);
    }
    
    /**
     * Check if user has accepted the privacy notification
     * 
     * @return boolean
     */
    Public Static function hasAcceptPN($user = null) {
        if (elgg_is_admin_logged_in()) {
            return true;
        }
        
        if (!($user instanceof \ElggUser)) {
            $user = elgg_get_logged_in_user_entity();
        }
        
        if ($user->pn_acceptance) {
            return true;
        }
        
        return false;
    }
    
    /** 
     * Check if privacy notication text has been entered in settings
     * 
     * @return boolean
     */
    Public Static function privacyNotificationIsSet() {
        $privacy_terms = elgg_get_plugin_setting('privacy_terms', self::PLUGIN_ID);
        if (!empty($privacy_terms)) {
            return true;
        }
        
        return false;
    }
    
    /** 
     * Get the privacy notication text which has been entered in settings 
     * @return boolean
     */
    Public Static function getPrivacyNotificationText() {
        $privacy_terms = elgg_get_plugin_setting('privacy_terms', self::PLUGIN_ID);
        if (!empty($privacy_terms)) {
            return $privacy_terms;
        }
        
        return false;
    }
    
    /** 
     * Check if privacy notification is enabled on settings
     * 
     * @return boolean
     */
    Public Static function isEnabledOnRegistrattion() {
        if (!self::privacyNotificationIsSet()) {
            return false;
        }
        
        $enable_on_registration = elgg_get_plugin_setting('enable_on_registration', self::PLUGIN_ID);
        if ($enable_on_registration == self::PARAM_YES) {
            return true;
        }
        
        return false;
    }
    
    // /** 
    //  * Check if privacy notification is enabled on settings
    //  * 
    //  * @return boolean
    //  */
    // Public Static function isAccountRemovalBtnEnabled() {
    //     if (!elgg_is_active_plugin("account_removal")) {
    //         return false;
    //     }
        
    //     $enable_remove_account = elgg_get_plugin_setting('enable_remove_account', self::PLUGIN_ID);

    //     if ($enable_remove_account == self::PARAM_YES) {
    //         return true;
    //     }
        
    //     return false;
    // }
    
    /**
     * Track user's browser
     * 
     * @return type
     */
    Public Static function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }  
    
    /**
     * Get invite url based on site url invite code
     * 
     * @param type $user
     * @return type
     */
    Public Static function getInviteUrl($user = null) {

        $url = elgg_get_site_url();
        if ($user instanceof \ElggUser) {
            $elements = array(
                'user_guid' => $user->getGUID(),
                'invitecode' => generate_invite_code($user->username),
            );
            
            $url = elgg_normalize_url("privacy_notification/index");
            return elgg_http_add_url_query_elements($url, $elements);
        }

        return $url;
    }
}
