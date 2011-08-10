<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dev Docs Library
 *
 * Currently this just holds the parse_docs_file() method.
 * When I have some more time there will be additional "utility"
 * type methods in this library that make the add-on a bit nicer
 * 
 * @package    Dev Docs
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/dev_docs.ee_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

class Docs_library
{
	
	/**
	 * 
	 */
	public function __construct()
	{
		$this->_EE =& get_instance();
	}
	// End function __construct()
	
	
	
	
	/**
	 * Parse docs file
	 *
	 * @param      string
	 * @access     public
	 * @return     void
	 */
	function parse_docs_file($filepath = FALSE)
	{
		// No need to go any further without a valid filepath
		if ( ! $filepath)
		{
			return;
		}
		
		/**
		 * Load our Textile class
		 * @link https://github.com/netcarver/textile
		 */
		require('classTextile.php');
		$textile = new Textile();
		
		// Get the flat file's contents
		$docs = file_get_contents($filepath);
		
		// Strip out the first h1 section which is only needed when opening the file directly
		$docs = preg_replace('/h1\..+-{3}/s', '', $docs);
		
		// "explode" the docs into sections based on instances of h1 lines
		$sections = preg_split("/(h1\.\s.+)/", $docs, NULL, PREG_SPLIT_DELIM_CAPTURE);
		
		// Unset our first array item because PHP makes it empty if the preg_split
		// subject begins with the pattern which ours does
		unset($sections[0]);
		
		// If we have an array, loop through it and build the "pages"
		if (is_array($sections) && count($sections) > 0)
		{
			foreach ($sections as $section) {
				// exit(substr($section, 0, 4));
				if (substr($section, 0, 4) == 'h1. ')
				{
					$headings[] = substr($section, 4);
				} else {
					$content[] = $textile->TextileThis($section);
				}
			}
		}
		
		// echo "<pre>";
		// print_r($headings);
		// print_r($content);
		// echo "</pre>";
		// exit;
		$this->_EE->load->model('dev_docs_model');
		$this->_EE->dev_docs_model->save_docs($headings, $content);
		
	}
	// End function parse_docs_file()
	
}
// End class Docs_library


/* End of file docs_library.php */
/* Location: ./system/expressionengine/third_party/dev_docs/libraries/docs_library.php */