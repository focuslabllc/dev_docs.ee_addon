<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dev Docs Model
 *
 * Database transactions for Dev Docs
 * 
 * @package    Dev Docs
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/dev_docs.ee_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

class Dev_docs_model {
	
	
	/**
	 * @var object  the EE "superobject"
	 */
	private $_EE;
	
	
	
	
	/**
	 * Constructor
	 * 
	 * @access    public
	 * @author    Erik Reagan  <erik@focuslabllc.com>
	 * @return    void
	 */
	public function __construct()
	{
		$this->_EE =& get_instance();
	}
	// End function __construct()
	
	
	
	
	/**
	 * Get cached timestamp
	 * 
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    int
	 */
	public function cached_timestamp()
	{
		$query = $this->_EE->db->get_where('dd_settings', array('key' => 'last_saved'));
		return ($query->num_rows() > 0) ? $query->row()->value : 0 ;
	}
	// End function cached_timestamp()
	
	
	
	
	/**
	 * Save new timestamp
	 * 
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @param     int   UNIX timestamp
	 * @return    void
	 */
	public function save_timestamp($timestamp = 0)
	{
		if ($this->_EE->db->get_where('dd_settings', array('key' => 'last_saved'))->num_rows() == 0)
		{
			// new row
			$this->_EE->db->insert('exp_dd_settings', array('key' => 'last_saved', 'value' => $timestamp)); 
		} else {
			// update row
			$this->_EE->db->where('key', 'last_saved')->update('dd_settings', array('value' => $timestamp));
		}
	}
	// End function save_timestamp()
	
	
	
	
	/**
	 * Clear docs
	 * 
	 * If our documentation file has been updated we need
	 * to empty the current "content" from our table completely
	 * before saving the newly parsed data
	 * 
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    void
	 */
	public function clear_current_docs()
	{
		$this->_EE->db->empty_table('exp_dd_doc_sections');
	}
	// End function clear_current_docs()
	
	
	
	
	/**
	 * Save new docs
	 * 
	 * Our docs have been updated so we re-save them.
	 * This is triggered after the parsing
	 * within libraries/docs_library.php
	 * 
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @param     array    "page" headings
	 * @param     array    "page" content
	 * @return    void
	 */
	public function save_docs($headings = array(), $content = array())
	{
		
		// A few checks first
		if (count($headings) == 0)
		{
			show_error('Headings array is empty for some reason');
		}
		if (count($content) == 0)
		{
			show_error('Content array is empty for some reason');
		}
		if (count($headings) !== count($content))
		{
			show_error('Headings array and Content array must have the same number of key/value stores');
		}
		
		// Grab the URL helper for the short_name value
		$this->_EE->load->helper('url');
		$rows = array();
		
		// Okay, let's do this
		foreach ($headings as $key => $heading) {
			$rows[] = array(
				'heading'    => $heading,
				'short_name' => url_title($heading, 'underscore', TRUE),
				'content'    => $content[$key],
			);
		}
		
		// Save our documentation to the database
		$this->_EE->db->insert_batch('exp_dd_doc_sections', $rows);
		
	}
	// End function save_docs();
	
	
	
	
	/**
	 * Get single page contents
	 * 
	 * Fetching a "page's" content from the database
	 * based on the url_title() generated "short_name" of
	 * its respective h1. value in the document. If the first
	 * segment is false then we're on the "home page" of the module CP
	 * which will just load the first h1. section of the document
	 * 
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @param     mixed   bool|string
	 * @return    object
	 */
	public function get_page_contents($short_name = FALSE)
	{
		if ( ! $short_name)
		{
			return $this->_EE->db->limit(1)
			                     ->get('exp_dd_doc_sections')
			                     ->row();
		} else {
			$result = $this->_EE->db->limit(1)
			                        ->where('short_name', $short_name)
			                        ->get('exp_dd_doc_sections')
			                        ->row();
			// If we have a short_name value but it doesn't match a page, return
			// the first page by running this method again with a parameter of FALSE
			return (count($result) == 0) ? $this->get_page_contents(FALSE) : $result ;
		}
	}
	// End function get_page_contents()
	
	
	
	
	/**
	 * Get all pages
	 * 
	 * This is used to build the module's menu so we want
	 * an array as a result
	 * 
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    array
	 */
	public function get_pages()
	{
		return $this->_EE->db->select(array('heading', 'short_name'))
			                   ->get('exp_dd_doc_sections')
			                   ->result_array();
	}
	// End function get_pages()
	
}
// End class Dev_docs_model

/* End of file dev_docs_model.php */
/* Location: ./system/expressionengine/third_party/dev_docs/models/dev_docs_model.php */