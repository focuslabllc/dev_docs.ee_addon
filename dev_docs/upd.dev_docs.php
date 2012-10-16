<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dev Docs Update file
 *
 * The upd file contains the methods for installing the add-on
 * 
 * @package    Dev Docs
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/dev_docs.ee_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

require_once(PATH_THIRD . 'dev_docs/config/dev_docs.php');

class Dev_docs_upd { 
	
	
	/**
	 * @var string  add-on version number
	 */
	var $version = DD_VERSION;
	
	
	
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
		
		// $this->_EE->load->add_package_path('dev_docs');
		$this->_EE->load->add_package_path(APPPATH . '../third_party/dev_docs');
		$this->_EE->load->add_package_path(PATH_THIRD . 'dev_docs');
		$this->_EE->load->model('dev_docs_setup_model','Dev_docs_setup_model');
		$this->_EE->load->config('dev_docs');
		
	}
	// End function __construct()
	
	
	
	
	/**
	 * Install our add-on
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     bool
	 */
	public function install() 
	{
		$this->_install_module();
		return TRUE;
	}
	// End function install()
	
	
	
	
	/**
	 * Uninstall add-on
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     bool
	 */
	public function uninstall()
	{
		$this->_uninstall_module();
		return TRUE;
	}
	// End function uninstall()
	
	
	
	
	/**
	 * Update add-on
	 * 
	 * Run each update necessary between version updates
	 * 
	 * @param      string  current version number
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     bool
	 */
	public function update($current = '')
	{
		// No updates yet...
		return FALSE;
	}
	// End function update()
	
	
	
	
	/**
	 * Install module
	 *
	 * @access     private
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	private function _install_module()
	{
		
		// Actions for our install transaction
		// $actions = array(
		// 	array(
		// 		'class'   => '',
		// 		'method'  => ''
		// 	)
		// );
		$actions = FALSE;
		
		// Install our module
		$this->_EE->Dev_docs_setup_model->insert_module($this->_EE->config->item('dd:module_data'), $actions);
		// Build our custom db tables
		$this->_EE->Dev_docs_setup_model->create_dd_tables();
		
	}
	// End function _install_module()
	
	
	/**
	 * Uninstall module
	 *
	 * @access     private
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	private function _uninstall_module()
	{
		$this->_EE->Dev_docs_setup_model->delete_module();
		$this->_EE->Dev_docs_setup_model->drop_dd_tables();
	}
	// End function _uninstall_module()
}
// End class Dev_docs_upd

/* End of file upd.dev_docs.php */
/* Location: ./system/expressionengine/third_party/dev_docs/upd.dev_docs.php */