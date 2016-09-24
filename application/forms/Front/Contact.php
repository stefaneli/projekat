<?php

class Application_Form_Front_Contact extends Zend_Form
{
    public function init() {
        
        $firstName = new Zend_Form_Element_Text('first_name');
        
        $firstName->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(true)
                ->addErrorMessage('Ime je obavezno polje');
        
        $this->addElement($firstName);
        
        $email = new Zend_Form_Element_Text('email');
        $email->addFilter('StringTrim')
                ->addValidator('EmailAddress', false, array('domain' => false))
                ->setRequired(true)
                ->addErrorMessage('Email je obavezno polje');
        $this->addElement($email);
        
        $phone = new Zend_Form_Element_Text('phone');
        
        $phone->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(false);
        
        $this->addElement($phone);
        
        $subject = new Zend_Form_Element_Text('subject');
        
        $subject->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(false);
        
        $this->addElement($subject);
        
        $message = new Zend_Form_Element_Textarea('message');
        $message->addFilter('StringTrim')
                ->setRequired(true)
                ->addErrorMessage('Poruka je obavezno polje');
        $this->addElement($message);
    }

}
