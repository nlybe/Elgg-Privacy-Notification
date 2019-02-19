<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

elgg_require_js('privacy_notification/privacy_notification');

// do nothing if privacy notification hasn't been set
if (!PrivacyNotificationOptions::privacyNotificationIsSet()) {
    return $return;
}

$user_guid = elgg_extract('user_guid', $vars, '');
$user = get_entity($user_guid);
if (!($user instanceof \ElggUser)) {
    $user = elgg_get_logged_in_user_entity();
}
        
if (!($user instanceof \ElggUser)) {
    return; 
}
   
// do not show the form if user has accept the notification
if (PrivacyNotificationOptions::hasAcceptPN($user)) {
    return;      
}

echo elgg_format_element('p', ['id' => 'privacy_intro'], elgg_echo('privacy_notification:index:intro', [$user->name]));
echo elgg_format_element('div', ['id' => 'privacy_terms'], elgg_format_element('div', [], PrivacyNotificationOptions::getPrivacyNotificationText()));

// view to extend to add more fields to the acceptance form
echo elgg_view('privacy_notification/acceptance/extend', $vars);

?>


<div class="elgg-foot">
<?php

    echo elgg_view('input/hidden', array('name' => 'user_guid', 'value' => $user->getGUID()));

    if (PrivacyNotificationOptions::isAccountRemovalBtnEnabled()) {
        echo elgg_view('output/url', array(
            'id' => 'remove_account_btn',
            'href' => elgg_normalize_url("account_removal/$user->username"),
            'class' => 'elgg-button elgg-button-submit elgg-button-delete elgg-size-small',
            'text' => elgg_echo('privacy_notification:btn:remove_account'),
            'title' => elgg_echo('privacy_notification:btn:remove_account'),
        ));
    }
    
    $btn = elgg_view_field([
        '#type' => 'submit',
        'id' => 'privacy_terms_btn',
        'disabled' => 'disabled',
        'value' => elgg_echo('privacy_notification:btn:accept')
    ]);
    
    echo elgg_format_element('div', 
        ['class' => 'privacy_terms_btn_box'], 
        $btn
    );
?>
</div>

