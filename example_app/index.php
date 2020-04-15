<?php 
require_once("inc/functions.php");


$requests = $_GET;
$hmac = $_GET['hmac'];
$serializeArray = serialize($requests);
$requests = array_diff_key($requests, array( 'hmac' => '' ));
ksort($requests);

$token = "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";
$shop = "myheroku";

//Product and Product Images
$image = "";
$title = "";

$collectionList = shopify_call($token, $shop, "/admin/api/2020-04/custom-collections.json", array(), 'GET');
$collectionList = json_decode($collectionList['response'], JSON_PRETTY_PRINT);
$collection_id = $collectionList['custom_collections'][0]['id'];

$collects = shopify_call($token, $shop, "/admin/api/2020-04/collects.json", array("collection_id"=>$collection_id), "GET");
$collects = json_decode($collects['response'], JSON_PRETTY_PRINT);

foreach ($collects as $collect) {
	foreach($collect as $key => $value) {
		$products = shopify_call($token, $shop, "/admin/api/2020-04/products/" . $value['product_id'] . ".json", array(), "GET");
		$products = json_decode($products['response'], JSON_PRETTY_PRINT);

		$images = shopify_call($token, $shop, "/admin/api/2020-04/products/" . $value['product_id'] . "/images.json", array(), "GET");
		$images = json_decode($images['response'], JSON_PRETTY_PRINT);


		 $image = $images['images'][0]['src'];
         $title = $products['product']['title'];
         $count++;
	}
}

foreach ($theme as $curr_theme) {
	foreach($curr_theme as $key => $value) {
		if($value['role'] === 'main') {

			echo "Theme ID: " . $value['id'] . "<br/>";
			echo "Theme Name: " . $value['name'] . "<br/>";

			$array = array(
   				"asset" => array(
 					"key" => "templates/index.liquid",
 					"value" => "Hello Wordl from WeeklyHow"
   				)
			);

			$assets = shopify_call($token, $shop, "/admin/api/2020-04/themes/" . $value['id'] . "/assets.json", array(), "PUT");
		    $assets = json_decode($assets['response'], JSON_PRETTY_PRINT);

		}
	}
}


$script_array = array(
 					"script_tag" => array(
 						"event" => "onload",
 						"src" => "https://shopifyapp10.herokuapp.com/example_app/scripts/script.js"
 					)
);

$scriptTag = shopify_call($token, $shop, "/admin/api/2020-04/themes/script_tags.json", array(), "POST");
$scriptTag = json_decode($scriptTag['response'], JSON_PRETTY_PRINT);


 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Shopify Example App</title>
 </head>
 <body>
 	<h1>Shopify Example App 2</h1>
 	<img src="<?php echo $image; ?>" style="width:250px;">
 	<p><?php echo $title; ?></p>
 </body>
 </html>
