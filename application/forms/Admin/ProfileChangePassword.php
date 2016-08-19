<?php

class Application_Form_Admin_ProfileChangePassword extends Zend_Form
{
	public function init() {
		
		$newPassword = new Zend_Form_Element_Password('new_password');
		$newPassword->addValidator('StringLength', false, array('min' => 5, 'max' => 255))
			->setRequired(true);
		$this->addElement($newPassword);
		
		$newPasswordConfirm = new Zend_Form_Element_Password('new_password_confirm');
		$newPasswordConfirm->addValidator('Identical', false, array(
			'token' => 'new_password',
			'messages' => array(
				Zend_Validate_Identical::NOT_SAME => 'Passwords do not match'
			)
		))
			->setRequired(true);
		$this->addElement($newPasswordConfirm);
	}
}