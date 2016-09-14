<?php

class Zend_View_Helper_CategoryLeadingPhotoUrl extends Zend_View_Helper_Abstract
{
        /**
     * 
     * @param type $category
     * @return string
     */
    public function categoryLeadingPhotoUrl($category) {

        $categoryImgFileName = $category['id'] . '.jpg';  // '-' . date("Y-m-d") .

        $categoryImgFilePath = PUBLIC_PATH . '/uploads/categories/' . $categoryImgFileName;
        // Helper ima property view koji je Zend View
        // i preko kojeg pozivamo ostale view helpere
        // na primer $this->view->baseUrl();
        if (is_file($categoryImgFilePath)) {
            return $this->view->baseUrl('/uploads/categories/' . $categoryImgFileName . '?' . time()) ; // dodali smo time() da bi dodali na kraj parameta
                                                                                                   // vreme kako bi prevarili browser da refresuje uvek sliku
        } else {
            return '';
        }
    }
}