<?php
/**
 * River view for discussion replies
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$reply = $item->getObjectEntity();
$subject = $item->getSubjectEntity();
$target = $item->getTargetEntity();

if (PrivacyNotificationOptions::anonymizeUser($subject)) {
    $subject_link = elgg_echo('privacy_notification:anonymize:user:label');
}
else {
    $subject_link = elgg_view('output/url', array(
        'href' => $subject->getURL(),
        'text' => $subject->name,
        'class' => 'elgg-river-subject',
        'is_trusted' => true,
    ));  
}


$target_link = elgg_view('output/url', array(
	'href' => $target->getURL(),
	'text' => $target->getDisplayName(),
	'class' => 'elgg-river-target',
	'is_trusted' => true,
));

$reply_link = elgg_view('output/url', array(
	'href' => $reply->getURL(),
	'text' => elgg_echo('river:reply:view'),
	'class' => 'elgg-river-target',
	'is_trusted' => true,
));

$summary = elgg_echo('river:reply:object:discussion', array($subject_link, $target_link));

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'message' => elgg_get_excerpt($reply->description). ' ' .$reply_link,
	'summary' => $summary,
));
