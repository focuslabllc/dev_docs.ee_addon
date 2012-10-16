<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dev Docs Accessory
 *
 * Hook into the CP Menu and add a Dev Docs flyout
 *
 * @package    Dev Docs
 * @author     Minds On Design Lab, Inc. <dev@mod-lab.com>
 * @copyright  Copyright (c) 2012 Minds On Design Lab, Inc.
 * @link       https://github.com/Minds-On-Design-Lab/dev_docs.ee_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

require_once(PATH_THIRD . 'dev_docs/config/dev_docs.php');

class Dev_docs_acc {
	var $name        = "Dev Docs Accessory";
	var $id          = 'dev_docs_acc';
	var $version     = '1.0';
	var $description = "Adds a Dev Docs dropdown menu to the CP main menu";
	var $sections    = array();
	/**
	 * Constructor
	 */
	public function Dev_docs_acc() {
		$this->EE =& get_instance();

		$this->EE->load->add_package_path(PATH_THIRD . 'dev_docs');
		$this->EE->load->config('dev_docs');
		$this->EE->load->model('dev_docs_model');
		$this->EE->load->library('Docs_library');
		// Lang file isn't auto-loaded for some reason
		$this->EE->lang->loadfile('dev_docs');
		
		$this->_url_base = $this->EE->config->item('dd:mod_url_base');
	}

	public function set_sections() {

		$r = '';
		$this->sections[] = '<script type="text/javascript" charset="utf-8">$("#accessoryTabs a.dev_docs_acc").parent().remove();</script>';

		$installed_modules = $this->EE->cp->get_installed_modules();


		if(array_key_exists('dev_docs', $installed_modules)) {

			$pages = $this->EE->dev_docs_model->get_pages();

			foreach ($pages as $page) {

				$title = $page['heading'];
				$link = $this->_url_base . AMP . 'docs_page=' . $page['short_name'];
				$r .= '<li><a href=\''. $link .'\'>'. $title .'</a></li>';

			}


			$this->EE->cp->add_to_head('
				<script type="text/javascript">
					$(document).ready(function(){

						var ddpages = "'.$r.'";
						var dev_docs_menu = "<li class=\'parent\'><a class=\'first_level\' href=\'#\'>Dev Docs</a><ul>" + ddpages + "<li class=\'bubble_footer\'></li></ul></li>";

						$("ul#navigationTabs > li.parent:nth-child(3)").before(dev_docs_menu);

					});
				</script>
			');

		}

	}

	public function update() {
		return TRUE;
	}

}

/* End of file acc.dev_docs.php */
/* Location: ./system/expressionengine/third_party/dev_docs/acc.dev_docs.php */ 