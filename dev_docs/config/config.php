<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dev Docs Configuration
 *
 * We store many settings in the config file. All are
 * prefixed for clarity throughout the system and config dumps
 * when other devs are debugging.
 * 
 * @package    Dev Docs
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/dev_docs.ee_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */



$addon_config = array(
	'prefix'      => 'dd',
	'full_name'   => 'Dev Docs',
	'short_name'  => 'dev_docs',
	'version'     => '0.1.6',
	'author_url'  => 'http://focuslabllc.com',
	'docs_url'    => 'https://github.com/focuslabllc/dev_docs.ee_addon',
	'description' => 'Parse the project Developer Documentation within the CP for easy reading'
);


// Setup constants for the base config settings
foreach ($addon_config as $key => $value)
{
	$name = strtoupper($addon_config['prefix'] . '_' . $key);
	if ( ! isset($config[$name]))
	{
		$config[$name] = $value;
	}
	if ( ! defined($name))
	{
		define($name, $value);
	}
}

/* End of file dev_docs.php */
/* Location: ./system/expressionengine/third_party/dev_docs/config/dev_docs.php */