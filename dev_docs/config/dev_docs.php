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


/**
 * @var    string   Version number
 * 
 * Also define the version constant to be
 * used in the extension and accessory files
 */
$config['dd:version'] = "0.1.6";
if ( ! defined('DD_VERSION'))
{
	define('DD_VERSION',$config['dd:version']);
}


/**
 * @var    string   Description of module
 */
$config['dd:description'] = "Parse the project Developer Documentation within the CP for easy reading";


/**
 * @var    string   URL base for inner add-on linking
 * 
 * We use the conditional so that the config value is only set within the CP
 */
if (defined('BASE'))
{
	$config['dd:mod_url_base'] = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=dev_docs';
}


/**
 * @var     array  For installing the module
 */
$config['dd:module_data'] = array(
	'module_name'        => 'Dev_docs',
	'module_version'     => $config['dd:version'],
	'has_cp_backend'     => 'y',
	'has_publish_fields' => 'n'
);


/**
 * @var    array   Default extension settings
 */
$config['dd:default_settings'] = array();


/* End of file dev_docs.php */
/* Location: ./system/expressionengine/third_party/dev_docs/config/dev_docs.php */