<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dev Docs Extension
 *
 * Hook into the CP Menu and add a Dev Docs flyout
 *
 * @package    Dev Docs
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/dev_docs.ee_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

require_once(PATH_THIRD . 'dev_docs/config/dev_docs.php');

class Dev_docs_ext {
	
	/**
	 * @var string  Extension name
	 */
	public $name = 'Dev Docs';
	
	
	/**
	 * @var string  Extension version number
	 */
	public $version = DD_VERSION;
	
	
	/**
	 * @var string  Extension description
	 */
	public $description = 'CP Menu addition for Dev Docs module';
	
	
	/**
	 * @var string  Do Extensions exist? (y|n)
	 */
	public $settings_exist = 'n';
	
	
	/**
	 * @var string  Extensions documentation URL
	 */
	public $docs_url = 'http://focuslabllc.com/';
	
	
	/**
	 * @var array  Extension settings array
	 */
	public $settings = array();
	
	
	/**
	 * @var object  The EE super object to be referenced in our {@link __construct()}
	 */
	private $_EE;
	
	
	/**
	 * @var string  Base URL for inner-module linking
	 */
	private $_url_base;
	
	
	
	/**
	 * Constructor
	 *
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @param     mixed     Settings array or empty string if none exist.
	 * @return    void
	 */
	public function __construct($settings='')
	{
		
		$this->_EE =& get_instance();
		
		// load some goodies
		$this->_EE->load->add_package_path(PATH_THIRD . 'dev_docs');
		$this->_EE->load->config('dev_docs');
		$this->_EE->load->model('dev_docs_model');
		$this->_EE->load->library('Docs_library');
		// Lang file isn't auto-loaded for some reason
		$this->_EE->lang->loadfile('dev_docs');
		
		$this->_url_base = $this->_EE->config->item('dd:mod_url_base');
		
	}
	// End function __construct()
	
	
	
	
	/**
	 * Add to the CP Menu
	 *
	 * @param      array
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     array
	 */
	public function cp_menu_array($menu)
	{
		
		if ($this->_EE->extensions->last_call !== FALSE)
		{
			$menu = $this->_EE->extensions->last_call;
		}
		
		// Query to get our menu titles
		$pages = $this->_EE->dev_docs_model->get_pages();
		foreach ($pages as $page) {
			$this->_EE->lang->language['nav_'.$page['short_name']] = $page['heading'];
			$menu['dev_docs'][$page['short_name']] = $this->_url_base . AMP . 'docs_page=' . $page['short_name'];
		}
		
		return $menu;
		
	}
	// End function cp_menu_array()
	
	
	
	
	/**
	 * Basic settings method
	 *
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    array
	 */
	public function settings()
	{
		// Default settings are stored in config/dev_docs.php
		return $this->_EE->config->item('dd:default_settings');
	}
	// End function settings()
	
	
	
	
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 * Or does it.....(see below)
	 *
	 * @access    public
	 * @return    void
	 */
	public function activate_extension()
	{
		
		/**
		 * We actually have to install with at least 1 hook otherwise the extension
		 * doesn't actually get installed. It makes sense to a degree - but we're just
		 * using the extension file to store our add-on's settings. Hopefully
		 * one day this won't exist.
		 * @link http://expressionengine.com/forums/viewthread/176691/
		 */
		
		/**
		 * We aren't using this to activate the extension. We can guarantee the installation of
		 * our extension by doing this in the module UPD file. That way a user can't choose to not
		 * install the extension which would kill our settings approach. So, nothing to see here.
		 */

	}
	// End function activate_extension()
	
	
	
	
	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 * Or does it.....(see above)
	 *
	 * @access    public
	 * @return    void
	 */
	public function disable_extension()
	{
		// Nothing here either. See comments for activate_extension()
	}
	// End function disable_extension()
	
}
// End class Dev_docs_ext

/* End of file ext.dev_docs.php */
/* Location: ./system/expressionengine/third_party/dev_docs/ext.dev_docs.php */