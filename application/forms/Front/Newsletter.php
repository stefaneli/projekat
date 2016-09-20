<?php

class Application_Form_Front_Newsletter extends Zend_Form
{
    public function init() {
        
        $email = new Zend_Form_Element_Text('email');
        $email->addFilter('StringTrim')
                ->addValidator('EmailAddress', false, array('domain' => false))
                ->setRequired(true);
        $this->addElement($email);
    }

}

