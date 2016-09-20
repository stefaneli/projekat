<?php

class Zend_View_Helper_CategoryLeadingPhotoTitle extends Zend_View_Helper_Abstract
{
        /**
     * 
     * @param type $category
     * @return string
     */
    public function categoryLeadingPhotoTitle($category) {

        return $categoryImgFileName = 'salon lepote mirijevo ' . $category['title'];  // '-' . date("Y-m-d") .

       
    }
}