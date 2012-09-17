<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dev_docs_acc {
	var $name        = "Dev Docs";
	var $id          = 'dev_docs_acc';
	var $version     = '1.0';
	var $description = "This is an accessory that displays a menu for the Dev Docs sections";
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

		if ($this->EE->extensions->last_call !== FALSE)
		{
			$menu = $this->EE->extensions->last_call;
		}

		$menu_out = '<ul>';
		
		// Query to get our menu titles
		$pages = $this->EE->dev_docs_model->get_pages();
		foreach ($pages as $page) {
			$title = $this->EE->lang->language['nav_'.$page['short_name']];
			$link = $this->_url_base . AMP . 'docs_page=' . $page['short_name'];
			// $this->EE->lang->language['nav_'.$page['short_name']] = $page['heading'];
			// $menu['dev_docs'][$page['short_name']] = $this->_url_base . AMP . 'docs_page=' . $page['short_name'];
			$menu_out .= '<li><a href="'. $link .'">'. $title .'</a></li>';
		}
		$menu_out .= '</ul>';
		

		$this->sections["Dev Docs Sections"] = $menu_out;

	}
}

/* End of file acc.dev_docs.php */
/* Location: ./system/expressionengine/third_party/first_contact/acc.dev_docs.php */ å