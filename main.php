<?php

require_once 'icontact-config.php';
require_once 'util.php';
require_once 'lib.php';

date_default_timezone_set('America/New_York');
// workaround for default security settings?
ini_set('allow_url_fopen', 'On');

$prospectListId = 23140;
$customerListId = 23139;

$xml_document = file_get_contents('php://input');

// For testing:
//$in = fopen( 'php://stdin', 'r' );
//while(!feof($in)){
//    $xml_document .= fgets($in, 4096);
//}

// Parse the XML Document into a DOM Object
$doc = new DOMDocument();
$doc->loadXML($xml_document);

// There should be only one export element, but we're doing this
// the proper way just in case.
$exports = $doc->getElementsByTagName("export");
foreach ($exports as $export) {
    $orders = $export->getElementsByTagName("order");

	foreach($orders as $order) {
        $email = $order->getElementsByTagName("email")->item(0)->nodeValue;

        $mailing_list = $order->getElementsByTagName("mailing_list")->item(0)->nodeValue;

        $aResult = callResource("/a/{$accountId}/c/{$clientFolderId}/contacts?status=normal&listId=$prospectListId&email=$email", 'GET');
        $aContact = $aResult['data']['contacts'][0];
        print_r($aContact);
        if ($aContact) {
            echo "moving $email from prospect list to customer list.\n";
            moveContactToList($aContact, $prospectListId, $customerListId);
        } else { // add if ($mailing_list == 'Y') to implement opt-in
            $firstName = $order->getElementsByTagName("bill_to_first_name")->item(0)->nodeValue;
            $lastName = $order->getElementsByTagName("bill_to_last_name")->item(0)->nodeValue;
            $street = $order->getElementsByTagName("bill_to_address1")->item(0)->nodeValue;
            $street2 = $order->getElementsByTagName("bill_to_address2")->item(0)->nodeValue;
            $city = $order->getElementsByTagName("bill_to_city")->item(0)->nodeValue;
            $state = $order->getElementsByTagName("bill_to_state")->item(0)->nodeValue;
            $postalCode = $order->getElementsByTagName("bill_to_zip")->item(0)->nodeValue;
            $phone = $order->getElementsByTagName("day_phone")->item(0)->nodeValue;
            $fax = $order->getElementsByTagName("fax")->item(0)->nodeValue;
            $business = $order->getElementsByTagName("bill_to_company")->item(0)->nodeValue;
            echo "adding $email as new contact\n";
            $aContact = addContact($email, 'normal', null, $firstName, $lastName, null, $street, $street2, $city, $state, $postalCode, $phone, $fax, $business);
            print_r($aContact);
            echo "subscribing $email to customer list\n";
            $aSubscriptions = subscribeContactToList($aContact['contactId'], $customerListId);
            print_r($aSubscriptions);
        }
    }
}

?>
