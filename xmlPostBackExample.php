<?php
// Copyright (C) 2009 BPS Info Solutions, Inc.
// This software is licensed under the CC-GNU LGPL version 2.1 or later.
// Full license is available at http://creativecommons.org/licenses/LGPL/2.1/

// Sample implementation to parse the data sent by UltraCart when using the 
// XML Post Back functionality.

// Read in the XML document from the post data
$xml_document = file_get_contents('php://input');
$file = dirname(__FILE__) . '/order-' . time() . '.xml';
file_put_contents($file, $xml_document);
die;

$out = '';

// Parse the XML Document into a DOM Object
$doc = new DOMDocument();
$doc->loadXML($xml_document);

// Now let's extract and echo out some of the key fields.

// There should be only one export element, but we're doing this
// the proper way just in case.
$exports = $doc->getElementsByTagName("export");
foreach ($exports as $export) {
	$orders = $export->getElementsByTagName("order");
	
	// Now let's iterate through the order object and extract & echo out some key
	// fields.
	
	foreach($orders as $order) {
		// Basic order info
		$orderId = $order->getElementsByTagName("order_id")->item(0)->nodeValue;
        $email = $order->getElementsByTagName("email")->item(0)->nodeValue;
		$shipToFirstName = $order->getElementsByTagName("ship_to_first_name")->item(0)->nodeValue;
		$shipToLastName = $order->getElementsByTagName("ship_to_last_name")->item(0)->nodeValue;
		
		$out .= "<b>$orderId - Placed By $shipToFirstName $shipToLastName ($email)</b><br>";
		
		// Now let's get the items and print out some info about them.
        $out .= "<b>Items in Order</b><ul>";
		$items = $order->getElementsByTagName("item");
		foreach($items as $item) {
			$itemId = $item->getElementsByTagName("item_id")->item(0)->nodeValue;
			$quantity = $item->getElementsByTagName("quantity")->item(0)->nodeValue;
			$description = $item->getElementsByTagName("description")->item(0)->nodeValue;
            $out .= "<li>$itemId ($description) - Quantity $quantity</li>";
		}

        $out .= "</ul>";
	}
	
}

$file = dirname(__FILE__) . '/order-' . time() . '.html';
file_put_contents($file, $out);

?>