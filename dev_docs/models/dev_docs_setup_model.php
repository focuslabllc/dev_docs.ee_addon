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

class Dev_docs_setup_model {
	
	
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
		$this->_EE->load->dbforge();
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
		$this->_EE->db->insert('modules', $mod_data);
		// Actions
		if ($action_array)
		{
			foreach ($action_array as $action) {
				$this->_EE->db->insert('actions', $action);
			}
		}		
	}
	// End function insert_module()
	
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
		if ($this->_EE->db->table_exists('dd_doc_sections') && $this->_EE->db->table_exists('dd_settings'))
		{
			return;
		}
		
		if ( ! $this->_EE->db->table_exists('dd_doc_sections'))
		{
			$table1_fields = array(
				'id'         => array('type' => 'INT', 'constraint' => '10', 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'heading'    => array('type' => 'VARCHAR', 'constraint' => '255'),
				'short_name' => array('type' => 'VARCHAR', 'constraint' => '255'),
				'content'    => array('type' => 'TEXT')
			);
			$this->_EE->dbforge->add_field($table1_fields);
			$this->_EE->dbforge->add_key('id', TRUE);
			$this->_EE->dbforge->create_table('dd_doc_sections');
		}
		
		if ( ! $this->_EE->db->table_exists('dd_settings'))
		{
			$table2_fields = array(
				'key'           => array('type' => 'VARCHAR', 'constraint' => '255'),
				'value'         => array('type' => 'TEXT'),
				'is_serialized' => array('type' => 'INT', 'constraint' => '1', 'default' => '0')
			);
			$this->_EE->dbforge->add_field($table2_fields);
			$this->_EE->dbforge->create_table('dd_settings');
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
		$this->_EE->db->select('module_id');
		$query = $this->_EE->db->get_where('modules', array('module_name' => 'Dev_docs'));
		
		$this->_EE->db->where('module_id', $query->row('module_id'))
		              ->delete('module_member_groups');
		
		$this->_EE->db->where('module_name', 'Dev_docs')
		              ->delete('modules');
		
		$this->_EE->db->where('class', 'Dev_docs_mcp')
		              ->delete('actions');
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
		if ($this->_EE->db->table_exists('dd_dev_docs'))
		{
			$this->_EE->dbforge->drop_table('dd_dev_docs');
		}
		if ($this->_EE->db->table_exists('dd_settings'))
		{
			$this->_EE->dbforge->drop_table('dd_settings');
		}
		if ($this->_EE->db->table_exists('dd_doc_sections'))
		{
			$this->_EE->dbforge->drop_table('dd_doc_sections');
		}
	}
	// End function drop_dd_tables()
	
}
// End class Dev_docs_setup_model

/* End of file dev_docs_setup_model.php */
/* Location: ./system/expressionengine/third_party/dev_docs/models/dev_docs_setup_model.php */