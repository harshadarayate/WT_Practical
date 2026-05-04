<?php
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->WasteManagement->requests;

$requests = $collection->find(['status' => 'Pending']);

echo "<h2>Pending Waste Collection Tasks</h2>";
foreach ($requests as $request) {
    echo "Type: " . $request['waste_type'] . " | ";
    echo "Location: " . $request['location'] . " | ";
    echo "ID: " . $request['_id'] . "<br>";
}
?>