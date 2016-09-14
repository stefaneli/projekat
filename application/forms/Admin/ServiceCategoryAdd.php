<?php

class Application_Form_Admin_ServiceCategoryAdd extends Zend_Form
{
    
    public function init() {
       
        $title = new Zend_Form_Element_Text('title');
        //$title->addFilter(new Zend_Filter_StringTrim());
        //$title->addValidator(new Zend_Validate_StringLength(array('min' => 3, 'max' => 255)));
        
        $title->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(true);
        
        $this->addElement($title);
        
        $categoryLeadingPhoto = new Zend_Form_Element_File('category_leading_photo');
        $categoryLeadingPhoto->addValidator('Count', true, 1) 
                ->addValidator('MimeType', true, array('image/gif', 'image/jpeg', 'image/png'))
                ->addValidator('ImageSize', false, array(
                    'minwidth' => 360,
                    'maxwidth' => 2000,
                    'minheight' => 270,
                    'maxheight' => 2000
                ))
                ->addValidator('Size', false, array(
                    'max' => '10MB'
                    ))
                // disable move file to destination when calling method getValues
                ->setValueDisabled(true)
                ->setRequired(true);
        $this->addElement($categoryLeadingPhoto);
        
        
    }

    
    
}
