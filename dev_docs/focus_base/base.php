<?php

class Focus_base {


	protected $addon_config = array();

	protected $addon_short_name = '';



	/**
	 * 
	 */
	protected function __construct()
	{

		// Setup access to EE's core object
		if ( ! isset($this->EE))
		{
			$this->EE =& get_instance();
		}

		if ( ! isset($addon_config))
		{
			$addon_config = array();
			// Require our config file to set our base properties
			require(dirname(__FILE__) . '/../config/config.php');
			$this->addon_short_name = $addon_config['short_name'];

			if (is_array($addon_config) && count($addon_config) > 0)
			{
				foreach ($addon_config as $key => $value)
				{
					$this->set($key, $value);
				}
			}
		}
		
	}
	// End function __construct()




	/**
	 * Create CP url bases
	 * 
	 * @param     string     base URL type (mcp, ext, acc)
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @access    protected
	 * @return    string
	 */
	protected function url_base($type = 'mcp')
	{
		switch ($type)
		{
			case 'ext' :
				$url = BASE.AMP. 'C=addons_extensions' .AMP. 'M=extension_settings' .AMP. 'file=' . $this->get('short_name');
			break;

			case 'mcp' :
			default    :
				$url = BASE.AMP. 'C=addons_modules' .AMP. 'M=show_module_cp' .AMP. 'module=' . $this->get('short_name');
			break;
		}

		return $url;
	}
	// End function url_base()




	/**
	 * 
	 */
	protected function set($key, $value)
	{
		$this->{$key} = $value;
		$this->EE->session->cache[$this->addon_short_name][$key] = $value;
	}
	// End function set()




	/**
	 * 
	 */
	protected function get($key)
	{
		if (isset($this->EE->session->cache[$this->addon_short_name][$key]))
		{
			return $this->EE->session->cache[$this->addon_short_name][$key];
		}
		elseif (isset($this->{$key}))
		{
			return $this->{$key};
		} else {
			return FALSE;
		}
	}
	// End function get()

}
// End class Focus_base