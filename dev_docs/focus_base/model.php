<?php

/**
 * Focus Lab base for Module Control Panel
 * 
 * 
 */

require_once('base.php');

class Focus_base_model extends Focus_base {
	

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}
	// End function __construct()


	/**
	 * 
	 */
	public function get_setting($key = false)
	{
		if ( ! $key)
		{
			return FALSE;
		}

		// See if there's a config.php setting equivalent and use that if so
		if ($this->EE->config->item($this->get('prefix') . ':' . $key))
		{
			return $this->EE->config->item($this->get('prefix') . ':' . $key);
		}
		
		return FALSE;
	}
	// End function get_setting()

}
// End class Focus_base_model