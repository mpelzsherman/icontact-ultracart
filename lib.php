<?php

function moveContactToList($aContact, $currentList, $destinationList) {
    global $accountId, $clientFolderId;
	echo "moving contact to destination list:\n" . var_export($aContact, true) . "\n";
    $contactId = $aContact['contactId'];
	$sData = "<subscription><listId>{$destinationList}</listId></subscription>";
    $subscriptionId = "{$currentList}_{$contactId}";
	callResource("/a/$accountId/c/$clientFolderId/subscriptions/$subscriptionId", 'PUT', $sData, 'xml', false);
}

function addContact($sEmail, $sStatus = 'normal', $sPrefix = null, $sFirstName = null, $sLastName = null, $sSuffix = null, $sStreet = null, $sStreet2 = null, $sCity = null, $sState = null, $sPostalCode = null, $sPhone = null, $sFax = null, $sBusiness = null) {
    global $accountId, $clientFolderId;

    // Valid statuses
    $aValidStatuses = array('normal', 'bounced', 'donotcontact', 'pending', 'invitable', 'deleted');
    // Contact placeholder
    $aContact = array(
        'email' => $sEmail
    );
    // Check for a prefix
    if (!empty($sPrefix)) {
        // Add the new prefix
        $aContact['prefix'] = (string) $sPrefix;
    }
    // Check for a first name
    if (!empty($sFirstName)) {
        // Add the new first name
        $aContact['firstName'] = (string) $sFirstName;
    }
    // Check for a last name
    if (!empty($sLastName)) {
        // Add the new last name
        $aContact['lastName'] = (string) $sLastName;
    }
    // Check for a suffix
    if (!empty($sSuffix)) {
        // Add the new suffix
        $aContact['suffix'] = (string) $sSuffix;
    }
    // Check for a street
    if (!empty($sStreet)) {
        // Add the new street
        $aContact['street'] = (string) $sStreet;
    }
    // Check for a street2
    if (!empty($sStreet2)) {
        // Add the new street 2
        $aContact['street2'] = (string) $sStreet2;
    }
    // Check for a city
    if (!empty($sCity)) {
        // Add the new city
        $aContact['city'] = (string) $sCity;
    }
    // Check for a state
    if (!empty($sState)) {
        // Add the new state
        $aContact['state'] = (string) $sState;
    }
    // Check for a postal code
    if (!empty($sPostalCode)) {
        // Add the new postal code
        $aContact['postalCode'] = (string) $sPostalCode;
    }
    // Check for a phone number
    if (!empty($sPhone)) {
        // Add the new phone number
        $aContact['phone'] = (string) $sPhone;
    }
    // Check for a fax number
    if (!empty($sFax)) {
        // Add the new fax number
        $aContact['fax'] = (string) $sFax;
    }
    // Check for a business name
    if (!empty($sBusiness)) {
        // Add the new business
        $aContact['business'] = (string) $sBusiness;
    }
    // Check for a valid status
    if (!empty($sStatus) && in_array($sStatus, $aValidStatuses)) {
        // Add the new status
        $aContact['status'] = $sStatus;
    } else {
        $aContact['status'] = 'normal';
    }

    $result = callResource("/a/$accountId/c/$clientFolderId/contacts", 'POST', array($aContact));
    return $result['data']['contacts'][0];
}

function subscribeContactToList($iContactId, $iListId, $sStatus = 'normal') {
    global $accountId, $clientFolderId;
    // Setup the subscription and make the call
    $aSubscriptions = callResource("/a/$accountId/c/$clientFolderId/subscriptions", 'POST', array(
        array(
            'contactId' => $iContactId,
            'listId'    => $iListId,
            'status'    => $sStatus
        )
    ));
    // Return the subscription
    return $aSubscriptions;
}

function checkContactStatus($accountId, $clientFolderId, $aProspect) {
	$iContactId = $aProspect['contactId'];
	$aResponse = callResource("/a/{$accountId}/c/{$clientFolderId}/contacts/{$iContactId}", 'GET');
	//var_export($aResponse);
	$sStatus = $aResponse['data']['contact']['status'];
	$bStatusIsNormal = ($sStatus == "normal");
	return $bStatusIsNormal;
}

?>