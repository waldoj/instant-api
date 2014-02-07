<?php


/**
 * Instant API class
 *
 * This is the Instant API class, which contains all of the methods required to run the program.
 *
 * LICENSE: MIT
 *
 * @author Waldo Jaquith <waldo@jaquith.org>
 * @copyright 2014 Waldo Jaquith
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/waldoj/instant-api/
 *
 */
class InstantAPI
{
	
	/**
	 * Retreives data and caches it, immediately upon loading
	 *
	 * Every time we instantiate InstantAPI(), we want to retrieve data (and possibly cache it).
	 *
	 * @return	boolean	TRUE or FALSE, FALSE if data could be retrieved, parsed, or cached.
	 */
	function __construct()
	{
		$this->id = filter_input(INPUT_GET, 'id');
		$result = $this->retrieve_data();
		if ($result === FALSE)
		{
			$result = $this->parse_json();
			if ($result === FALSE)
			{
				die('Cannot parse JSON.');
			}
			$result = $this->cache_data();
			if ($result === FALSE)
			{
				die('Cannot cache data.');
			}
			$result = $this->retrieve_data();
			if ($result === FALSE)
			{
				die('Cannot retrieve data from cache.');
			}
		}
	}
	
	/**
	 * Parse data from a JSON file
	 *
	 * Extract all data from the JSON file and pivot it to be indexed by the specified field.
	 *
	 * @return	boolean	TRUE or FALSE, FALSE if JSON could not parsed.
	 */
	function parse_json()
	{
		
		/*
		 * A field name to be indexed must be specified.
		 */
		if (defined('INDEXED_FIELD') === FALSE)
		{
			return FALSE;
		}
		
		/*
		 * Load the file in as an object.
		 */
		$data = json_decode(file_get_contents(JSON_FILE));
		
		if ($data == FALSE)
		{
			return FALSE;
		}
		
		/*
		 * Duplicate the object, creating a new one that uses the committee ID as the key.
		 */
		$tmp = new stdClass();
		foreach($data as &$record)
		{
		
			$tmp->{$record->{INDEXED_FIELD}} = $record;
			
			/*
			 * Save memory.
			 */
			unset($record);
			
		}
		
		/*
		 * Swap variables.
		 */
		unset($data);
		$this->data = $tmp;
		unset($tmp);
		
		return TRUE;
		
	} // end method parse_json()
	
	/**
	 * Cache data
	 *
	 * Store the JSON file's constituent data within the configured cache mechanism.
	 *
	 * @return	boolean	TRUE or FALSE, FALSE if JSON could not cached.
	 */
	function cache_data()
	{
	
		if (CACHE_TYPE === FALSE)
		{
			return TRUE;
		}
		
		elseif (CACHE_TYPE == 'serialize')
		{
			$result = file_put_contents(CACHE_DIRECTORY . 'cache', serialize($this->data));
			if ($result === FALSE)
			{
				return FALSE;
			}
		}
		
		elseif (CACHE_TYPE == 'apc')
		{
		
			/*
			 * Iterate through all of the records and store each of them within APC.
			 */
			foreach ($this->data as $id => $record)
			{
				apc_store($id, $record);
			}
			
		}
		
		elseif (CACHE_TYPE == 'json')
		{
			
			/*
			 * Iterate through all of the records and store each of them within the filesystem.
			 */
			foreach ($this->data as $id => $record)
			{
				file_put_contents(CACHE_DIRECTORY . $id .'.json', json_encode($record));
			}
			
		}
			
		return TRUE;
	
	} // end method cache_data
	
	
	/**
	 * Retrieve cached data
	 *
	 * Get cached data from the configured cache mechanism.
	 *
	 * @return	boolean	TRUE or FALSE, FALSE if JSON could not cached.
	 */
	function retrieve_data()
	{
		
		if (CACHE_TYPE === FALSE)
		{
			$this->parse_json();
			return TRUE;
		}
		
		elseif (CACHE_TYPE == 'serialize')
		{
			
			if (file_exists(CACHE_DIRECTORY . 'cache') === FALSE)
			{
				return FALSE;
			}
			
			$result = file_get_contents(CACHE_DIRECTORY . 'cache');
			if ($result === FALSE)
			{
				return FALSE;
			}
			
			$this->data = unserialize($result);
			return TRUE;
			
		}
		
		elseif (CACHE_TYPE == 'apc')
		{
		
			apc_fetch($this->id, $result);
			if ($result === FALSE)
			{
				return FALSE;
			}
			$this->record = $record;
			unset($record);
			return TRUE;
			
		}
		
		elseif (CACHE_TYPE == 'json')
		{
		
			if (file_exists(CACHE_DIRECTORY . $this->id .'.json') === FALSE)
			{
				return FALSE;
			}
			
			readfile(CACHE_DIRECTORY . $this->id .'.json');
			exit;
			
		}
		
	} // end method retrieve_data
	
} // end class Server

/**
 * Format an error as JSON
 *
 * Return a JSON-formatted error message, to permit parsing and rendering by the client.
 *
 * @return	string	JSON-formatted data
 */
function json_error($message = 'A fatal error occurred.', $http_code = '400 Bad Request')
{
	header('HTTP/1.0 ' . $http_code);
	echo json_encode( array( 'error' => $message ) );
}
