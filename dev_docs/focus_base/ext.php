<?php

/**
 * Focus Lab base for Extension files
 * 
 * 
 */

require_once('base.php');

class Focus_base_ext extends Focus_base {


	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->name        = $this->get('full_name');
		$this->version     = $this->get('version');
		$this->docs_url    = $this->get('docs_url');
		$this->description = $this->get('description');
	}
	// End function __construct()

}
// End class Focus_base_ext