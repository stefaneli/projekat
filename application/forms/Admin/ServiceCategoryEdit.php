<?php

class Application_Form_Admin_ServiceCategoryEdit extends Application_Form_Admin_ServiceCategoryAdd
{
	public function init() {
		parent::init();
		
		$this->getElement('category_leading_photo')->setRequired(false);
	}
}