<?php

require '../vendor/autoload.php';

use Google\Cloud\Language\V1\Entity;
use Google\Cloud\Language\V1\EntityMention;
use Google\Cloud\Language\V1\TextSpan;
use Google\Cloud\Language\V1\TextAnnotation;
use Google\Cloud\Language\V1\AnnotateTextResponse;

function getCitiesInState($apiKey, $stateName) {
    // Initialize the Google Places API client.
    $client = new \GooglePlaces\Client($apiKey);

    // Define the search query.
    $query = "cities in " . $stateName;

    // Perform a text search using the query.
    $places = $client->textSearch($query);

    // Extract city names from the results.
    $cities = [];
    foreach ($places as $place) {
        $cities[] = $place->name;
    }

    return $cities;
}

// Replace with your API key and desired state name.
$apiKey = 'YOUR_API_KEY';
$stateName = 'California';

$citiesInState = getCitiesInState($apiKey, $stateName);

// Print the list of cities.
echo "Cities in $stateName: \n";
foreach ($citiesInState as $city) {
    echo "- $city\n";
}
