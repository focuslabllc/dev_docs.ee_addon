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
	function parse_docs_file($filepath = FALSE)
	{
		
		// No need to go any further without a valid filepath
		if ( ! $filepath)
		{
			return;
		}
		
		// Get the file extension of our documentation file
		$file_ext = substr(strrchr($filepath,'.'), 1);
		
		// Make sure the filetype is supported and the parser is built in to this library
		if ( ! array_key_exists($file_ext, $this->parsers) OR ! method_exists(__CLASS__, 'parse_' . $file_ext))
		{
			show_error('The file type you have specified (' . $file_ext . ') is not currently supported.');
		}
		
		
		/**
		 * Use the filetype's parser
		 *
		 * Looks like we're good to go, so we'll parse
		 * it out and send it along to our model
		 */
		$parse_type = 'parse_' . $file_ext;
		$docs = $this->$parse_type($filepath);
		
		
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
		
		$this->_EE->load->model('dev_docs_model');
		$this->_EE->dev_docs_model->save_docs($headings, $content);
		
	}
	// End function parse_docs_file()
	
	
	
	
	/**
	 * Parse Textile
	 * 
	 * @param      string     file's absolute path
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     string
	 */
	public function parse_textile($filepath = FALSE)
	{
		require('parsers/textile/classTextile.php');
		$textile = new Textile();
		$docs = file_get_contents($filepath);
		
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
	public function parse_md($filepath = FALSE)
	{
		require('parsers/md/markdown.php');
		$docs = file_get_contents($filepath);
		
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
	public function parse_html($filepath = FALSE)
	{
		$docs = file_get_contents($filepath);
		$replace_array = array('/^.+<body>/s', '/<\/body>.+$/s');
		$docs = preg_replace($replace_array, '', $docs);
		
		return $docs;
	}
	// End function parse_html()
	
}
// End class Docs_library


/* End of file docs_library.php */
/* Location: ./system/expressionengine/third_party/dev_docs/libraries/docs_library.php */