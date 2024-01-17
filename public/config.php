<?php
require __DIR__ . '/../vendor/autoload.php';
$uri = "mongodb://localhost:27017";

// Create a MongoDB client
$client = new MongoDB\Client($uri);

// Select a database
$database = $client->data; // Replace with your actual database name

// Select a collection
$collection = $database->users; // Replace with your actual collection name

?>

