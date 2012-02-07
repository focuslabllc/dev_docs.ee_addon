<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dev Docs Setup Model
 *
 * Database transactions required for installing, uninstalling
 * and saving settings for the Dev Docs add-on 
 * 
 * @package    Dev Docs
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/dev_docs.ee_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

require_once(dirname(__FILE__) . '/../focus_base/model.php');

class Dev_docs_setup_model extends Focus_base_model {
	
	
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
		$this->EE->load->dbforge();
	}
	// End function __construct()
	
	
	
	
	/**
	 * Insert module into db
	 * 
	 * This installs the module & actions
	 * 
	 * @param     array  Module data
	 * @param     mixed  Multi-dimensional array of action install data or FALSE
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    void
	 */
	public function insert_module($mod_data, $action_array = FALSE)
	{
		$this->EE->db->insert('modules', $mod_data);
		// Actions
		if ($action_array)
		{
			foreach ($action_array as $action) {
				$this->EE->db->insert('actions', $action);
			}
		}		
	}
	// End function insert_module()
	
	
	
	
	/**
	 * Insert Extension
	 * 
	 * Activate extension
	 * This is usually in the ext file but I want to guarantee
	 * that the ext is installed with the module so it's in the
	 * upd file instead.
	 * 
	 * @param     array  Default extension settings
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    void
	 */
	public function insert_extension($data)
	{
		$this->EE->db->insert('extensions', $data);
	}
	// End function insert_extension()
	
	
	
	
	/**
	 * Delete Extension
	 * 
	 * Activate extension
	 * This is usually in the ext file but I want to guarantee
	 * that the ext is installed with the module so it's in the
	 * upd file instead.
	 * 
	 * @param     array  Default extension settings
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    void
	 */
	public function delete_extension()
	{
		$this->EE->db->where('class', 'Dev_docs_ext')
		              ->delete('extensions');
	}
	// End function delete_extension()
	
	
	
	
	/**
	 * Create tables
	 * 
	 * Use DB forge to create our new table
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	public function create_dd_tables()
	{
		if ($this->EE->db->table_exists('dd_doc_sections') && $this->EE->db->table_exists('dd_settings'))
		{
			return;
		}
		
		if ( ! $this->EE->db->table_exists('dd_doc_sections'))
		{
			$table1_fields = array(
				'id'         => array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'short_name' => array('type' => 'VARCHAR', 'constraint' => '255'),
				'file_name'  => array('type' => 'VARCHAR', 'constraint' => '255'),
				'sub_dir'    => array('type' => 'VARCHAR', 'constraint' => '255'),
				'heading'    => array('type' => 'VARCHAR', 'constraint' => '255'),
				'content'    => array('type' => 'TEXT'),
				'site_id'    => array('type' => 'INT', 'default' => 1),
			);
			$this->EE->dbforge->add_field($table1_fields);
			$this->EE->dbforge->add_key('id', TRUE);
			$this->EE->dbforge->create_table('dd_doc_sections');
		}
		
		if ( ! $this->EE->db->table_exists('dd_settings'))
		{
			$table2_fields = array(
				'key'           => array('type' => 'VARCHAR', 'constraint' => '255'),
				'value'         => array('type' => 'TEXT'),
				'site_id'       => array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'default' => '1'),
				'is_serialized' => array('type' => 'TINYINT', 'constraint' => '1', 'default' => '0')
			);
			$this->EE->dbforge->add_field($table2_fields);
			$this->EE->dbforge->add_key('key', TRUE);
			$this->EE->dbforge->create_table('dd_settings');
		}
	}
	// End function create_dd_table()
	
	
	
	
	/**
	 * Delete module from db
	 * 
	 * This deletes the module & actions
	 * 
	 * @param     array  Module data
	 * @param     mixed  Multi-dimensional array of action install data or FALSE
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    void
	 */
	public function delete_module()
	{
		$this->EE->db->select('module_id');
		$query = $this->EE->db->get_where('modules', array('module_name' => 'Dev_docs'));
		
		$this->EE->db->where('module_id', $query->row('module_id'))
		             ->delete('module_member_groups');
		
		$this->EE->db->where('module_name', 'Dev_docs')
		             ->delete('modules');
		
		$this->EE->db->where('class', 'Dev_docs_mcp')
		             ->delete('actions');
		
		$this->drop_dd_tables();
	}
	// End function delete_module()
	
	
	
	
	/**
	 * Drop database tables
	 * 
	 * Use DB forge to drop our custom tables
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	public function drop_dd_tables()
	{
		// deprecated table
		// @todo - remove dd_dev_docs from method before 1.0 release
		if ($this->EE->db->table_exists('dd_dev_docs'))
		{
			$this->EE->dbforge->drop_table('dd_dev_docs');
		}
		if ($this->EE->db->table_exists('dd_settings'))
		{
			$this->EE->dbforge->drop_table('dd_settings');
		}
		if ($this->EE->db->table_exists('dd_doc_sections'))
		{
			$this->EE->dbforge->drop_table('dd_doc_sections');
		}
	}
	// End function drop_dd_tables()
	
}
// End class Dev_docs_setup_model

/* End of file dev_docs_setup_model.php */
/* Location: ./system/expressionengine/third_party/dev_docs/models/dev_docs_setup_model.php */