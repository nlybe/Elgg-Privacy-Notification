Privacy Notification
====================

![Elgg 5.0](https://img.shields.io/badge/Elgg-5.0-orange.svg?style=flat-square)

Display privacy notification for community members. Useful for General Data Protection Regulation (GDPR) compliance.

## Features

- After login the user should accept the privacy policy before be able to navigate as logged-in user.
- User should scroll down the notification text in order to be able to accept the privacy notification.
- Administrator can set the text of privacy notifications, which users have to accept, in plugin settings. If this text is empty, users are not notified about privacy policy.
- Option to add Account Removal button so user can remove/disable his account. This requires the [Account Removal](https://github.com/ColdTrick/account_removal) plugin.
- Option to list's of users who have accepted or not the privacy notification. For viewing lists, the [DataTables API](https://github.com/nlybe/Elgg-DataTablesAPI) plugin is suggested.
- Option to require acceptance of privacy notification on registration form.
- IP address and browser are tracked on privacy acceptance.
- Option to use invite link for users who haven't accepted the privacy notification yet.
- Option to anonymize users who haven't accept the privacy notification, if enabled in settings. Anonymized user are visible by administrators. ** This option is deprecated for Elgg v3.x.**

## Installation

Use composer to install this plugin. On site root folder, run the command:
```
composer require nlybe/privacy_notification
```

## About option to anonymize users (deprecated for Elgg v3.x)

It is suggested to enable this option only on existing communities where users have already submitted content. For new communities it will no have any affect.

If enable the Privacy Notification plugin, some of the existing users may not accept the privacy notification, so by enabling the "Anonymize Users" option in plugin settings these users will not be visible and accessible from other users but their content will still be available.

Also note that some modifications may be required for anonymizing users on content of 3rd party plugins.

## Acknowledgements

- Plugin was initially developed and sponsored by [Advatera](https://my.advatera.com/ "Advatera")

## Future improvements

- Add again the option to remove account
