<?php
/**
 * Elgg Privacy Notification plugin
 * @package privacy_notification
 */

namespace PrivacyNotification\Elgg;

use Elgg\DefaultPluginBootstrap;
use PrivacyNotification\PrivacyNotificationOptions;

class Bootstrap extends DefaultPluginBootstrap {
	
	const HANDLERS = [];

	/**
	 * {@inheritdoc}
	 */
	public function boot() {
		// Check if registered user have accept the privacy notification, while navigating
		$identifiers = PrivacyNotificationOptions::getSiteIdentifiers();
		foreach ($identifiers as $identifier) {
			elgg_register_plugin_hook_handler('route:rewrite', trim($identifier), 'privacy_notification_acceptance_check_nav');
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function init() {
		$this->initViews();
	}

	/**
	 * Init views
	 *
	 * @return void
	 */
	protected function initViews() {

		// Check after login if user has accept the privacy notification
		elgg_register_plugin_hook_handler('login:forward', 'user', 'privacy_notification_acceptance_check');

		// Save privacy notification acceptance on registration, if enabled
		elgg_register_plugin_hook_handler('register', 'user', 'privacy_notification_accept_on_registration');

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
		
	}
}
