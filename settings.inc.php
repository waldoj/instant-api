<?php

/**
 * Settings for Instant API
 *
 * LICENSE: MIT
 *
 * @author Waldo Jaquith <waldo@jaquith.org>
 * @copyright 2014 Waldo Jaquith
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/waldoj/instant-api/
 *
 */

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
 * serialized and stored as a file; "apc" to store each object property in APC; "memcached" to store
 * each object property in Memcached, and "json" to store each object property as an individual JSON file.
 */
define('CACHE_TYPE', 'json');

/*
 * If cached within the filesystem, in what directory should cached material be stored?
 */
define('CACHE_DIRECTORY', 'cache/');

/*
 * If using Memcached-based caching, what is the server and port for the Memcached server?
 */
define('MEMCACHED_SERVER', '127.0.0.1');
define('MEMCACHED_PORT', '11211');
