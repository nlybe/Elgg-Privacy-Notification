<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

return [

    'privacy_notification' => 'Privacy Notification',
    
    'privacy_notification:index:title' => 'Privacy Notification',
    'privacy_notification:index:intro' => 'Dear %s, you must accept the terms below in order to be able to browse on this site.<br />Scroll down while reading the privacy notification and activate the button to accept.',
    'privacy_notification:btn:accept' => 'Accept',
    'privacy_notification:btn:remove_account' => 'Account Removal',
    'privacy_notification:accept:set' => 'Set privacy acceptance',
    'privacy_notification:accept:unset' => 'Unset privacy acceptance',
    
    'privacy_notification:action:acceptance:removed' => "Privacy Notification marked as 'not accepted'",
    'privacy_notification:action:acceptance:added' => "%s, you successfully accepted Privacy Notification",
    'privacy_notification:action:acceptance:error:logged_out' => "Invalid permission for this action",
    
    'privacy_notification:registration:terms' => 'Privacy Notification',
    'privacy_notification:registration:label' => 'I have read and accept the %s',
    'privacy_notification:anonymize:user:label' => 'Hidden User',
    'privacy_notification:anonymize:user:note' => 'This user is hidden due to privacy notification policy',
    
    // admin area
    'admin:privacy_notification:datatable_api:missing' => 'The DataTables API plugin is not enabled',
    'admin:privacy_notification' => 'Privacy Notification',
    'menu:page:header:privacy_notification_section' => 'Privacy Notification',
    'admin:privacy_notification:users' => "User's List",
    'admin:privacy_notification:etype:accepted' => 'Users accepted privacy notification',
    'admin:privacy_notification:etype:not_accepted' => 'Users NOT accepted privacy notification',
    'privacy_notification:admin:menu:users:accepted' => 'Users accepted',
    'privacy_notification:admin:menu:users:not_accepted' => 'Users not accepted',
    'privacy_notification:admin:users:table:header:id' => 'ID',
    'privacy_notification:admin:users:table:header:name' => 'Name',
    'privacy_notification:admin:users:table:header:username' => 'Username',
    'privacy_notification:admin:users:table:header:email' => 'Email',
    'privacy_notification:admin:users:table:header:accepted' => 'Accepted',
    'privacy_notification:admin:users:table:header:ip' => 'IP Address',
    'privacy_notification:admin:users:table:header:browser' => 'Browser',
    'privacy_notification:admin:users:table:header:invite_url' => 'Invite URL',
    'privacy_notification:admin:users:table:header:actions' => 'Actions',
    'privacy_notification:admin:users:view_user' => 'View user',
    'admin:privacy_notification:no_results' => 'No results',
    'privacy_notification:admin:users:invite' => 'Invite URL',
    
    // settings
    'privacy_notification:settings:no' => "No",
    'privacy_notification:settings:yes' => "Yes", 
    'privacy_notification:settings:title' => 'Basic Configuration',
    'privacy_notification:settings:identifiers' => 'Pages restricted by privacy policy',
    'privacy_notification:settings:identifiers:help' => 'Enter a list (comma seperated) of identifiers for sections which user is not able to see before accept the privacy policy. Read more about routing and identifiers on Elgg <a href="http://learn.elgg.org/en/latest/guides/hooks-list.html#routing" target="_blank">here</a>.<br />Example: activity, groups, members, pages',
    'privacy_notification:settings:enable_on_registration' => 'Enable on registration form',
    'privacy_notification:settings:enable_on_registration:help' => 'Select Yes if want to enable the acceptance of privacy notification during registration form.',
    'privacy_notification:settings:privacy_terms' => 'Privacy Notification Text',
    'privacy_notification:settings:privacy_terms:help' => 'Enter the text for privacy notification which users have to accept.',
    // 'privacy_notification:settings:enable_remove_account' => 'Add "Account Removal" button',
    // 'privacy_notification:settings:enable_remove_account:help' => 'Select Yes if want to add the "Account Removal" button on privacy acceptance form. This requires the <a href="https://github.com/ColdTrick/account_removal" target="_blank">account_removal plugin</a>.',
    
];
