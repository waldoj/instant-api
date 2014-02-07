# Instant API

A PHP class that makes it trivial to pop up a RESTful, JSON-emitting API from a single JSON file. Intended for retrieving individual records from a JSON file that is comprised of many records. It indexes records by a specified field, which functions as the unique ID.

Can cache output as a single serialized object, as individual records in APC, or as individual JSON files on the filesystem.

If you'd prefer this in Python, @jbradforddillon [ported it and made some improvements](https://github.com/jbradforddillon/instant-api-py).

## Instructions
1. Install `index.php` and `class.InstantAPI.php` in a public directory on a web server.
1. Create a directory called `cache`, and give the web server permission to write to it and read from it.
1. Edit the configuration options at the head of `index.php`:
    * Specify the path to the JSON file as `JSON_FILE`. (You can just drop it in the same directory as `index.php` etc.)
    * Specify the name of the field in each JSON record that will function as the unique ID for each request as `INDEXED_FIELD`.

Requests must be in the format of `http://example.com/?id=[unique_id]`. Of course, the directory need not be named `instantapi`, and `mod_rewrite` can be used to eliminate `?id=` from the public URL, so the URL could read `http://example.com/person/jsmith.json`,  or `http://example.com/zipcode/90210.json`.

The first request will prime the cache, and then deliver the requested result. To refresh the cache, such as after updating the JSON file, simply delete all of the files in `cache/`.

## Example

To create an API for this JSON file, `committees.json`, with `CommitteeCode` as the unique ID:

```json
{
  "0": {
    "AccountId": "a1f8792b-3e82-e111-9bed-984be103f032",
    "CommitteeCode": "PP-12-00458",
    "CommitteeName": "10th District Republican Congressional Committee"
  },
  "1": {
    "AccountId": "92b38bad-2583-e111-9bed-984be103f032",
    "CommitteeCode": "PP-12-00366",
    "CommitteeName": "11th Congressional District Democratic Committee"
  },
  "2": {
    "AccountId": "69376bae-3e82-e111-9bed-984be103f032",
    "CommitteeCode": "PP-12-00457",
    "CommitteeName": "11th Congressional District of VA Republican Committee"
  },
  "3": {
    "AccountId": "341646c1-4082-e111-9bed-984be103f032",
    "CommitteeCode": "PP-12-00450",
    "CommitteeName": "1st District Republican Committee"
  },
  "4": {
    "AccountId": "2b5f88f6-aa7d-e111-9bed-984be103f032",
    "CommitteeCode": "PAC-12-00377",
    "CommitteeName": "2007 Conservative Victory Committee"
  }
}
```

Copy `committees.json` into the Instant API directory, set `JSON_FILE` to `committees.json`, and set `INDEXED_FIELD` to `CommitteeCode`. If `CACHE_TYPE` is kept at the default value of `json`, then loading `http://example.com/?id=PP-12-00458` will create five JSON files in `cache`, and then pass the contents of `/cache/PP-12-00458.json` directly to the browser. (The cache directory could be to say, `records`, and the URL `http://example.com/records/PP-12-00458.json` could be queried directly, loading that static file and eliminating the need to invoke Instant API at all.)


## Requirements
* PHP v5.2 or later.
