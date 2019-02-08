<?php

/**
 * Outputs object title
 * @uses $vars['title'] Title
 */

$title = elgg_extract('title', $vars);
if (!$title) {
    return;
}

$entity = $vars['entity'];
if (PrivacyNotificationOptions::anonymizeUser($entity)) {
    $title = elgg_echo('privacy_notification:anonymize:user:label');
}

?>
<h3 class="elgg-listing-summary-title"><?= $title ?></h3>