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
		
	}
}
