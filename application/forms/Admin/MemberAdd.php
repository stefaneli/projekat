<?php

class Application_Form_Admin_MemberAdd extends Zend_Form
{
    
    // Overajdovan init metoda
    public function init() {
        $firstName = new Zend_Form_Element_Text('first_name');
        //$firstName->addFilter(new Zend_Filter_StringTrim());
        //$firstName->addValidator(new Zend_Validate_StringLength(array('min' => 3, 'max' => 255)));
        
        $firstName->addFilter('StringTrim')
                ->setRequired(true);
        
        $this->addElement($firstName);
        
        
        
        $lastName = new Zend_Form_Element_Text('last_name');
        $lastName->addFilter('StringTrim')
                ->setRequired(true);
        $this->addElement($lastName);
        
        $workTitle = new Zend_Form_Element_Text('work_title');
        $workTitle->addFilter('StringTrim')
                ->setRequired(false);
        $this->addElement($workTitle);
        
        $email = new Zend_Form_Element_Text('email');
        $email->addFilter('StringTrim')
                ->addValidator('EmailAddress', false, array('domain' => false)) // na nivou elementa ovo false znaci da ce se  izvrsavati dalje validatori i ako ovaj prvi sa false ne prodje, ako je je true onda se nece izvrsavati nadalje
                ->setRequired(true);
        $this->addElement($email);
        
        $resume = new Zend_Form_Element_Textarea('resume');
        $resume->addFilter('StringTrim')
                ->setRequired(false);
        $this->addElement($resume);
        
        $memberPhoto = new Zend_Form_Element_File('member_photo');
        $memberPhoto->addValidator('Count', true, 1) 
                ->addValidator('MimeType', true, array('image/gif', 'image/jpeg', 'image/jpg', 'image/png'))
                //->addValidator('Extension', false, array('gif', 'jpeg', 'jpg', 'png'))
                ->addValidator('ImageSize', false, array(
                    'minwidth' => 150,
                    'maxwidth' => 2000,
                    'minheight' => 150,
                    'maxheight' => 2000
                ))
                ->addValidator('Size', false, array(
                    'max' => '10MB'
                    ))
                // disable move file to destination when calling method getValues
                ->setValueDisabled(true)
                ->setRequired(false);
        $this->addElement($memberPhoto);
        
    }

    
}
