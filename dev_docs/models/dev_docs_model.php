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

require_once(dirname(__FILE__) . '/../focus_base/model.php');

class Dev_docs_model extends Focus_base_model {
	
	
	/**
	 * Constructor
	 * 
	 * @access    public
	 * @author    Erik Reagan  <erik@focuslabllc.com>
	 * @return    void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->EE->load->config('dev_docs');
		$this->site_id = $this->EE->config->item('site_id');
	}
	// End function __construct()
	
	
	
	
	/**
	 * Get cached timestamp
	 * 
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    int
	 */
	public function get_setting($key = FALSE)
	{
		$config_value = parent::get_setting($key);

		if ($config_value)
		{
			return $config_value;
		}

		// If there's not a config override, we'll query the database for the setting
		$query = $this->EE->db->where('site_id', $this->site_id)
		                      ->where('key', $key)
		                      ->get('dd_settings');
		return ($query->num_rows() > 0) ? $query->row()->value : NULL ;
	}
	// End function get_setting()
	
	
	
	
	/**
	 * Save new timestamp
	 * 
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @param     string   setting string
	 * @param     string   setting value
	 * @param     int      1|0     "is serialized"
	 * @return    void
	 */
	public function save_setting($key = FALSE, $value = 0, $serialized = 0)
	{
		if ( ! $key)
		{
			return FALSE;
		}
		
		if ($this->EE->db->get_where('dd_settings', array('key' => $key))->num_rows() == 0)
		{
			// new row
			$this->EE->db->insert('exp_dd_settings', array('key' => $key, 'value' => $value, 'site_id' => $this->site_id, 'is_serialized' => $serialized)); 
		} else {
			// update row
			$this->EE->db->where('key', $key)->update('dd_settings', array('value' => $value, 'site_id' => $this->site_id, 'is_serialized' => $serialized));
		}
	}
	// End function save_setting()
	
	
	
	
	/**
	 * Clear docs
	 * 
	 * Delete the cached documentation for this specific site
	 * 
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    void
	 */
	public function clear_current_docs()
	{
		$this->EE->db->delete('exp_dd_doc_sections', array('site_id' => $this->site_id));
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
	public function save_docs($headings = array(), $content = array(), $file_name = '', $sub_dir = '')
	{
		
		// A few checks first
		if (count($headings) === 0)
		{
			show_error('Headings array is empty for some reason');
		}
		if (count($content) === 0)
		{
			show_error('Content array is empty for some reason');
		}
		if (count($headings) !== count($content))
		{
			show_error('Headings array and Content array must have the same number of key/value stores');
		}
		
		// Grab the URL helper for the short_name value
		$this->EE->load->helper('url');
		$rows = array();
		
		// Okay, let's do this
		foreach ($headings as $key => $heading) {
			$rows[] = array(
				'short_name' => url_title($heading, 'underscore', TRUE),
				'file_name'  => $file_name,
				'sub_dir'    => $sub_dir,
				'heading'    => $heading,
				'content'    => $content[$key]
			);
		}
		
		// Save our documentation to the database
		$this->EE->db->insert_batch('exp_dd_doc_sections', $rows);
		
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
			return $this->EE->db->limit(1)
			                    ->get('exp_dd_doc_sections')
			                    ->row();
		} else {
			$result = $this->EE->db->limit(1)
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
	 * Get submenu for page
	 * 
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @param     mixed   bool|string
	 * @return    object
	 */
	public function get_submenu($sub_dir = FALSE, $file_name = FALSE)
	{

		if ( ! $sub_dir && ! $file_name)
		{
			return FALSE;
		}
		
		if ($this->get_setting('doc_type') != 'dir')
		{
			return FALSE;
		}
		
		$this->EE->db->select(array('heading', 'short_name'));
		$this->EE->db->where('sub_dir', $sub_dir);
		// If the directory is empty we can limit by file_name
		// enabling single files in directories to break out into
		// multiple files
		if ($sub_dir == '')
		{
			$this->EE->db->where('file_name', $file_name);
		}
		$pages = $this->EE->db->get('exp_dd_doc_sections');
		
		// Bail out if there's only 1 page or none at all
		if ($pages->num_rows() <= 1)
		{
			return FALSE;
		}
		
		$pages = $pages->result();
		// exit(var_dump($pages));
		foreach ($pages as $key => $page) {
			$pages[$key]->url = $this->url_base() . AMP . 'docs_page=' . $page->short_name;
		}
		
		return $pages;
		
	}
	// End function get_submenu()
	
	
	
	
	/**
	 * Get all pages
	 * 
	 * This is used to build the module's menu so we want
	 * an array as a result
	 * 
	 * @access    public
	 * @param     string      menu|all
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    array
	 */
	public function get_pages($display = 'menu')
	{

		// Get the pages from our table (all pages, even within subdirectories)
		$pages = $this->EE->db->select(array('heading', 'file_name', 'short_name', 'sub_dir'))
		                      ->order_by('id', 'asc')
		                      ->get('exp_dd_doc_sections')
		                      ->result_array();
		
		if ($display == 'all')
		{
			return $pages;
		}
		
		// Loop through the pages catching subdirectories and
		// only using the first page for a subdirectory
		$sub_directories = array();
		foreach ($pages as $key => $page)
		{
			
			// Unset any key with a pre-defined subdirectory
			// If the subdirecory was cached then we already found
			// the first page
			if (in_array($page['sub_dir'], $sub_directories))
			{
				unset($pages[$key]);
			}
			
			// Now we'll save a subdirectory if we've stumbled across one but
			// haven't cached it yet
			if ($page['sub_dir'] != '')
			{
				$sub_directories[] = $page['sub_dir'];
			}
			
		}
		// End foreach ($pages as $key => $page) {}
		
		
		// If we're in "directory mode" we'll make sure we properly parse files
		// from within directories containing multiple h1 tags in individual files
		if ($this->get_setting('doc_type') == 'dir')
		{
			$file_names = array();
			foreach ($pages as $key => $page)
			{
				// Unset any key with a pre-defined subdirectory
				// If the subdirecory was cached then we already found
				// the first page
				if (in_array($page['file_name'], $file_names))
				{
					unset($pages[$key]);
				}

				// Now we'll save a subdirectory if we've stumbled across one but
				// haven't cached it yet
				if ($page['file_name'] != '')
				{
					$file_names[] = $page['file_name'];
				}
			}
			// End foreach ($pages as $key => $page) {}
		}
		// End if ($this->get_setting('doc_type') == 'dir') {}
		
		
		return $pages;

	}
	// End function get_pages()
	
	
	
	
	/**
	 * Check if docs exist in the DB yet
	 */
	public function docs_exist()
	{
		return ($this->EE->db->get_where('exp_dd_doc_sections', array('site_id' => $this->site_id))->num_rows() === 0)
		       ? FALSE
		       : TRUE ;
	}
	// End function docs_exist()
	
}
// End class Dev_docs_model

/* End of file dev_docs_model.php */
/* Location: ./system/expressionengine/third_party/dev_docs/models/dev_docs_model.php */