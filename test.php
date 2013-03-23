<?php

require 'icontact-config.php';
require 'util.php';
require 'lib.php';

$prospectListId = 23140;
$customerListId = 23139;

//$aResult = callResource("/a/{$accountId}/c/{$clientFolderId}/contacts?status=normal&listId=$prospectListId&email=mpelzsherman@gmail.com", 'GET');
//$aContact = $aResult['data']['contacts'][0];
//if ($aContact) {
//    moveContactToList($aContact, $prospectListId, $customerListId);
//}

$aPurchaserResponse = callResource("/a/{$accountId}/c/{$clientFolderId}/subscriptions?listId={$prospectListId}&status=normal&limit=10000&offset=0", 'GET');
print_r($aPurchaserResponse);

?>