<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dev Docs Library
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
	 * @var    array     supported formats
	 */
	private $parsers = array(
		'textile' => 'Textile',
		'md'      => 'Markdown',
		'html'    => 'HTML'
	);
	
	
	
	/**
	 * Class constructor
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
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
	function parse_docs($file_path = FALSE)
	{
		
		// No need to go any further without a valid file_path
		if ( ! $file_path)
		{
			return;
		}
		
		// Determine if we're working with a file or a directory
		$type = (is_dir($file_path)) ? 'dir' : 'file' ;
		
		// save docs type to settings table (file vs directory)
		$this->_EE->dev_docs_model->save_setting('doc_type', $type);
		
		$type_method = 'parse_' . $type;
		$this->$type_method($file_path);
		
	}
	// End function parse_docs_file()
	
	
	
	
	/**
	 * @todo - clean up this method. Yuck!
	 */
	public function parse_dir($file_path = FALSE, $depth = 1, $sub_dir = '', $depth_limit = 2)
	{
		if ( ! is_dir($file_path))
		{
			show_error('The path provided is not a directory.');
		}
		
		// For our recurrsion we shouldn't dive deeper than our depth limit
		if ($depth_limit - $depth < 0)
		{
			return;
		}
		
		// Load CodeIgnite's Directory helper if it hasn't been loaded yet
		if ( ! function_exists('directory_map'))
		{
			$this->_EE->load->helper('directory');
		}
		
		$map = directory_map($file_path, $depth_limit - $depth);
		
		// echo '<pre>';
		// print_r($map);
		// exit;
		
		foreach ($map as $key => $value) {
			// Create a depth integer based on the current depth plus 1
			$next_depth = $depth + 1;
			// Build our path for re-use a few times
			$new_path = $this->_EE->functions->remove_double_slashes($file_path . '/' . $value);
			
			// Loop through our key=>value stores to see if they are files or directories
			// If they are directories, we process them in this method recursively with a new depth
			if (is_array($value))
			{
				$this->parse_dir($this->_EE->functions->remove_double_slashes($file_path . '/' . $key), $next_depth, $key);
			} elseif(is_dir($new_path)) {
				$this->parse_dir($new_path, $next_depth, $value);
			} else {
				$this->parse_file($new_path, $value, $sub_dir);
			}
		}
		
	}
	// End function parse_dir()
	
	
	
	
	/**
	 * @todo - cache individual files by path+file_name?
	 * 
	 * @return    array    parsed docs array with headings and content
	 */
	public function parse_file($file_path = FALSE, $file_name = '', $sub_dir = '')
	{
		
		// Get the file extension of our documentation file
		$file_ext = substr(strrchr($file_path,'.'), 1);
		
		// Make sure the filetype is supported and the parser is built in to this library
		if ( ! array_key_exists($file_ext, $this->parsers) OR ! method_exists(__CLASS__, 'parse_' . $file_ext))
		{
			show_error('The file type you have specified (' . $file_ext . ') is not currently supported.');
		}
		
		// Create empty arrays for headings and content before parsing
		$headings = $content = array();
		
		
		/**
		 * Use the filetype's parser
		 *
		 * Looks like we're good to go, so we'll parse
		 * it out and send it along to our model
		 */
		$parse_type = 'parse_' . $file_ext;
		$docs = $this->$parse_type($file_path);
		
		
		// Strip out the first h1 section which is only needed when opening the file directly
		// this regex matches "anything from the beginning of the file (string) until ::start::</p>"
		// Then "anything after <p>::end::" (but supports attributes in the paragraph tag)
		// That means you could have something like <p class="hidden">::start</p> and
		// <p style="display:none">::end::</p>
		$docs = preg_replace(array('/^.+::start::<\/p>/s', '/<p(?:\s[a-zA-Z0-9]+=["|\'][a-zA-Z0-9-_:]+["|\'])?>::end.+/s'), '', $docs);
		
		// "explode" the docs into sections based on instances of h1 lines
		$sections = preg_split("/(<h1[.+]?>.+<\/h1>)/", $docs, NULL, PREG_SPLIT_DELIM_CAPTURE);
		// Unset our first array item because PHP makes it empty if the preg_split
		// subject begins with the pattern which ours does
		unset($sections[0]);
		
		// If we have an array, loop through it and build the "pages"
		if (is_array($sections) && count($sections) > 0)
		{
			foreach ($sections as $key => $value)
			{
				// If our key is even we're looking at content, otherwise it's a heading
				if ($key % 2 === 0)
				{
					$content[] = $value;
				} else {
					$headings[] = preg_replace('/<h1[.+]?>(.+)<\/h1>/', '$1', $value);
				}
			}
		}
		
		// echo '<pre>';
		// print_r($headings);
		// print_r($content);
		// exit;
		
		// exit(var_dump(empty($headings)));
		
		// If we don't have at least 1 heading and content block we're done
		// Though, this shouldn't happen if you write your documentation correctly :)
		if (empty($headings) OR empty($content))
		{
			return FALSE;
		}
		
		$this->_EE->load->model('dev_docs_model');
		$this->_EE->dev_docs_model->save_docs($headings, $content, $file_name, $sub_dir);
	}
	// End function parse_file()
	
	
	
	
	
	// ----------------------------------------------------------
	// Syntax Parsers
	// ----------------------------------------------------------
	
	
	
	
	
	/**
	 * Parse Textile
	 * 
	 * @param      string     file's absolute path
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     string
	 */
	public function parse_textile($file_path = FALSE)
	{
		if ( ! class_exists('Textile'))
		{
			require_once('parsers/textile/classTextile.php');
		}
		$textile = new Textile();
		$docs = file_get_contents($file_path);
		
		return $textile->TextileThis($docs);
	}
	// End function parse_textile()
	
	
	
	
	/**
	 * Parse Markdown
	 * 
	 * @param      string     file's absolute path
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     string
	 */
	public function parse_md($file_path = FALSE)
	{
		if ( ! function_exists('Markdown'))
		{
			require_once('parsers/md/markdown.php');
		}
		$docs = file_get_contents($file_path);
		
		return Markdown($docs);
	}
	// End function parse_md()
	
	
	
	
	/**
	 * Parse HTML
	 * 
	 * This one isn't really a parser. We just remove
	 * some of the unnecessary sections in an HTML document
	 * 
	 * @param      string     file's absolute path
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     string
	 */
	public function parse_html($file_path = FALSE)
	{
		$docs = file_get_contents($file_path);
		$replace_array = array('/^.+<body>/s', '/<\/body>.+$/s');
		$docs = preg_replace($replace_array, '', $docs);
		
		return $docs;
	}
	// End function parse_html()
	
}
// End class Docs_library


/* End of file docs_library.php */
/* Location: ./system/expressionengine/third_party/dev_docs/libraries/docs_library.php */