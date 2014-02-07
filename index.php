<?php

/*
 * Include the settings.
 */
require('settings.inc.php');

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
