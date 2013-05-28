# Instant API

A PHP class that makes it trivial to pop up a RESTful, JSON-emitting API from a single JSON file. Intended for retrieving individual records from a JSON file that is comprised of many records. It indexes records by a specified field, which functions as the unique ID.

Can cache output as a single serialized object, as individual records in APC, or as individual JSON files on the filesystem.

## Instructions
1. Install `index.php` and `class.InstantAPI.php` in a public directory on a web server.
1. Create a directory called `cache`, and give the web server permission to write to it and read from it.
1. Edit the configuration options at the head of `index.php`:
    * Specify the path to the JSON file as `JSON_FILE`. (You can just drop it in the same directory as `index.php` etc.)
    * Specify the name of the field in each JSON record that will function as the unique ID for each request.

Requests must be in the format of `http://example.com/?id=[unique_id]`. Of course, the directory need not be named `instantapi`, and `mod_rewrite` can be used to eliminate `?id=` from the public URL.

The first request will prime the cache, and then deliver the requested result. To refresh the cache, such as after updating the JSON file, simply delete all of the files in `cache/`.

## Requirements
* PHP v5.2 or later.

## License
Copyright Waldo Jaquith, 2013. Released under the MIT License.