<?php

/*
 * What is the JSON file that's to be the source of this API's data?
 */
define('JSON_FILE', '');

/*
 * What is the name of the field that will be queried via the API? That is, what is the unique ID?
 */
define('INDEXED_FIELD', '');

/*
 * What type of caching should be used? Valid options are: "false" (a literal, boolean FALSE) to
 * indicate that no caching should be used; "serialize" to indicate that the entire object should be
 * serialized and stored as a file; "apc" to store each object property in APC; and "json" to store
 * each object property as an individual JSON file.
 */
define('CACHE_TYPE', 'json');

/*
 * In what directory should cached material be stored?
 */
define('CACHE_DIRECTORY', 'cache/');

/*
 * Include the Instant API library.
 */
include('class.InstantAPI.php');

/*
 * All output will be as JSON.
 */
header('Content-Type: application/json');

/*
 * Require that an ID be present in the request.
 */
if (!isset($_GET['id']))
{
	json_error('No ID provided.');
	die();
}

/*
 * Create a new instance of Instant API.
 */
$server = new InstantAPI();

/*
 * If the requested ID doesn't exist, return a 404.
 */
if (!isset($server->data->{$server->id}))
{
	json_error('ID not found.', '404 Not Found');
	die();
}

/*
 * Return the record to the client.
 */
echo json_encode($server->data->{$server->id});
