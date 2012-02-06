<?php

/**
 * Focus Lab base for Module Control Panel
 * 
 * 
 */

require_once('base.php');

class Focus_base_mcp extends Focus_base {
	

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}
	// End function __construct()




	/**
	 * Build pagination
	 * 
	 * straight from the EE docs basically
	 *
	 * @param      mixed
	 * @param      int
	 * @param      int
	 * @access     private
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @link       http://expressionengine.com/user_guide/development/module_tutorial.html
	 * @return     array
	 */
	protected function pagination_config($method = FALSE, $per_page = 10, $total_rows = 0)
	{
		$config['base_url']   = $this->url_base();
		$config['base_url']  .= ($method) ? AMP . 'method=' . $method : '' ;
		$config['total_rows'] = $total_rows;
		$config['per_page']   = $per_page;

		$config['page_query_string']    = TRUE;
		$config['query_string_segment'] = 'rownum';


		// For the style we stick with EE's default pagination style

		$config['full_tag_open']  = '<p id="paginationLinks">';
		$config['full_tag_close'] = '</p>';

		$config['prev_link']  = '<img src="'
		                      . $this->EE->cp->cp_theme_url
		                      . 'images/pagination_prev_button.gif" width="13" height="13" alt="<" />';
		$config['next_link']  = '<img src="'
		                      . $this->EE->cp->cp_theme_url
		                      . 'images/pagination_next_button.gif" width="13" height="13" alt=">" />';
		$config['first_link'] = '<img src="'
		                      . $this->EE->cp->cp_theme_url
		                      . 'images/pagination_first_button.gif" width="13" height="13" alt="<<" />';
		$config['last_link']  = '<img src="'
		                      . $this->EE->cp->cp_theme_url
		                      . 'images/pagination_last_button.gif" width="13" height="13" alt=">>" />';

		return $config;
	}
	// End function pagination_config()

}
// End class Focus_base_mcp