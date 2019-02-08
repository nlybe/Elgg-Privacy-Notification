<?php
/**
 * Outputs object subtitle
 * @uses $vars['subtitle'] Subtitle
 */
$subtitle = elgg_extract('subtitle', $vars);
if (!$subtitle) {
    return;
}

$entity = $vars['entity'];
if (PrivacyNotificationOptions::anonymizeUser($entity)) {
    return;
}

?>
<div class="elgg-listing-summary-subtitle elgg-subtext"><?= $subtitle ?></div>