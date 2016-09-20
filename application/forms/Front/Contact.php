<?php

class Application_Form_Front_Contact extends Zend_Form
{
    public function init() {
        
        $firstName = new Zend_Form_Element_Text('first_name');
        
        $firstName->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(true);
        
        $this->addElement($firstName);
        
         $lastName = new Zend_Form_Element_Text('last_name');
        $lastName->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(true);
        $this->addElement($lastName);
        
    }

}
