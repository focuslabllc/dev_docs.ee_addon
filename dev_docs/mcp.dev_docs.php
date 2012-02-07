<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dev Docs Module CP
 * 
 * This add-on (so far) is filtered through a single method router
 * which uses a GET variable to determine which page to display
 * 
 * 
 * @package    Dev Docs
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/dev_docs.ee2_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

require_once('focus_base/mcp.php');

class Dev_docs_mcp extends Focus_base_mcp {


	/**
	 * Class constructor
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	public function __construct()
	{

		parent::__construct();
		
		// load some goodies
		$this->EE->load->add_package_path(PATH_THIRD . 'dev_docs');
		$this->EE->load->config('dev_docs');
		$this->EE->load->model('dev_docs_model');
		$this->EE->load->library('Docs_library');
		
	}
	// End function __construct()
	
	
	
	
	/**
	 * Router
	 * 
	 * All "pages" for this module are routed through this method
	 * We check for the file
	 *
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     string
	 */
	public function index()
	{
		
		// Grab our developer documentation. Will be a setting / config override down the road.
		$docs_path = $this->get_docs_path();
		$this->EE->dev_docs_model->save_setting('docs_path', $docs_path);
		
		// See if our cache needs clearing
		$this->cache_check($docs_path);
		
		
		/**
		 * Get our current page data
		 * 
		 * Query the DB for cached docs based on current page GET variable
		 * If we're on the homepage the parameter will be FALSE which the model method
		 * takes into consideration and just returns the first section of content
		 */
		$current_page = $this->EE->dev_docs_model->get_page_contents($this->EE->input->get('docs_page'));
		
		// Make sure we have pages to work with. If not, we'll display an error message
		if (empty($current_page))
		{
			show_error("Your documentation doesn't seem to have any valid content. Check your document(s) again.
			            <br><pre>" . $docs_path . "</pre>");
		}
		
		// Build our submenu if applicable
		$current_submenu = $this->EE->dev_docs_model->get_submenu($current_page->sub_dir, $current_page->file_name);

		$this->page_setup($current_page->heading, $current_submenu);
		
		$data['submenu'] = $current_submenu;
		$data['content'] = $current_page->content;
		
		return $this->EE->load->view('cp_index', $data, TRUE);
		
	}
	// End function index()




	/**
	 * Module Settings
	 */
	public function settings()
	{

		$data = array();

		$this->EE->load->model('member_model');

		$data['form_base'] = $this->url_base() . AMP . 'method=settings';
		$data['docs_path'] = $this->get_docs_path();
		$groups = $this->EE->db->select(array('group_id','group_title'))
		                       ->where('can_access_cp','y')
		                       ->get('exp_member_groups')
		                       ->result();
		$data['member_groups'] = $groups;
		$data['name_override'] = '';

		$this->page_setup(lang('dd:settings'));
		return $this->EE->load->view('settings', $data, TRUE);

	}
	// End function settings()





	/**
	 * Setup Page elements
	 */
	private function page_setup($heading = false, $submenu = array())
	{

		// Start off with a consistent breadcrumb addition
		$name = ($this->EE->config->item('dev_docs_cp_name'))
		      ? $this->EE->config->item('dev_docs_cp_name')
		      : lang('dev_docs_module_name') ;
		$this->EE->cp->set_breadcrumb($this->url_base(), $name);

		// Query to get our menu titles
		$pages = $this->EE->dev_docs_model->get_pages('menu');
		foreach ($pages as $page) {
			$menu_array[$page['heading']] = $this->url_base() . AMP . 'docs_page=' . $page['short_name'];
		}
		// $menu_array[lang('dd:settings')] = $this->url_base() . AMP . 'method=settings';

		// Some custom styles for better content display
		$theme_url = $this->EE->config->item('theme_folder_url') . 'third_party/dev_docs/';
		$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="' . $theme_url . 'dev_docs.css" />');
		
		if ( ! $heading)
		{
			$heading = $this->get('full_name');
		}

		if (is_array($submenu) && count($submenu) > 0)
		{
			// If we're on the parent page "overview" page we'll change the title
			if ($submenu[0]->short_name == $this->EE->input->get('docs_page'))
			{
				$heading = $submenu[0]->heading . ': ' . lang('dd:overview');
			} else {
				// Add a breadcrumb for our parent page
				$this->EE->cp->set_breadcrumb($submenu[0]->url, $submenu[0]->heading);
			}
		}
		
		$this->EE->cp->set_variable('cp_page_title', $heading);
		$this->EE->cp->set_right_nav($menu_array);

	}
	// End function page_setup()




	/**
	 * Get docs path
	 */
	private function get_docs_path()
	{
		$docs_path = $this->EE->dev_docs_model->get_setting('docs_path');
		// If it's an array we're working with MSM so we'll get the site-specific path
		if (is_array($docs_path))
		{
			if (isset($docs_path[$this->EE->config->item('site_id')]))
			{
				$docs_path = $docs_path[$this->EE->config->item('site_id')];
			} else {
				$docs_path = NULL;
			}
			
		}
		if ( ! file_exists($docs_path))
		{
			show_error('The developer documentation file (' . $docs_path . ') does not exist. Eventually this will be a specific view file but it\'s early in the add-on\'s development.');
		}

		return $docs_path;
	}
	// End function get_docs_path()
	



	/**
	 * Cache check
	 * 
	 * Check the modification time on the flat file against the cached modification time. We save
	 * the last modification time in the database for this conditional. If the file has been updated
	 * since the last cache then we'll re-parse the file and save all sections to the DB. With this
	 * approach to caching the file contents it won't have to read and parse through the file(s) each
	 * time a page loads in the module.
	 */
	private function cache_check($docs_path)
	{
		
		$path_changed = ($docs_path != $this->EE->dev_docs_model->get_setting('docs_path')) ? TRUE : FALSE ;
		$file_updated = (filemtime($docs_path) !== (int)$this->EE->dev_docs_model->get_setting('timestamp')) ? TRUE : FALSE ;
		$directory_mode = ($this->EE->dev_docs_model->get_setting('doc_type') == 'dir') ? TRUE : FALSE ;
		$docs_exist = $this->EE->dev_docs_model->docs_exist();
		
		// @todo - decide how to cache directory mode files
		if ( ! $docs_exist OR ($directory_mode === FALSE && ($path_changed OR $file_updated)) )
		{
			// delete doc rows
			$this->EE->dev_docs_model->clear_current_docs();
			// Re-parse and re-save the docs
			$this->EE->docs_library->parse_docs($docs_path);
			// save new timestamp to DB
			$this->EE->dev_docs_model->save_setting('timestamp', filemtime($docs_path));
		}

	}
	// End function cache_check()

}
// End class Dev_docs_mcp

/* End of file mcp.dev_docs.php */
/* Location: ./system/expressionengine/third_party/dev_docs/mcp.dev_docs.php */