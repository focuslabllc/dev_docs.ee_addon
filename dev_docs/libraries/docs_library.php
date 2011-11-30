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
        
        // Get the flat file's contents
        $docs = file_get_contents($filepath);
		
        // if it's a Textile file, grab and run Textile on it. Otherwise run Markdown and Smartypants.
		if(strpos($filepath,'.textile') > -1) {
            require('classTextile.php');
            $textile = new Textile();
            $docs = $textile->TextileThis($docs);
            }
            
        else {
            require('markdown.php');
            require('smartypants.php');
            $docs = Markdown($docs);
            $docs = SmartyPants($docs);
            }
            
        // "explode" the docs into sections based on instances of h1 lines
        $sections = preg_split("/(<h1.*?>.*?<\/h1>)/", $docs, NULL, PREG_SPLIT_DELIM_CAPTURE);
        
        // If our first item is blank, unset it. Otherwise, insert a generic <h1> in front of it.
        if(trim($sections[0]) == '') {
            unset($sections[0]);
            }
        else {
            $sections = array_merge(array('<h1>Overview</h1>'),$sections);
            }    
        
        foreach ($sections as $section) {
            // exit(substr($section, 0, 4));
            if (substr($section, 0, 3) == '<h1') {
                $headings[] = preg_replace('/<h1.*?>(.*?)<\/h1>/s', "$1", $section,1);
                } 
            else {
                $content[] = trim($section);
                }
            }

		$this->_EE->load->model('dev_docs_model');
		$this->_EE->dev_docs_model->save_docs($headings, $content);
		
	}
	// End function parse_docs_file()
	
}
// End class Docs_library


/* End of file docs_library.php */
/* Location: ./system/expressionengine/third_party/dev_docs/libraries/docs_library.php */