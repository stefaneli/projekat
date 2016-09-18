<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->view->activePage = 'index';
    }

    public function indexAction()
    {
		$cmsIndexSlidesDbTable = new Application_Model_DbTable_CmsIndexSlides();
		
		$indexSlides = $cmsIndexSlidesDbTable->search(array(
			'filters' => array(
				'status' => Application_Model_DbTable_CmsIndexSlides::STATUS_ENABLED
			),
			'orders' => array(
				'order_number' => 'ASC'
			)
		));
		
                $cmsServicesCategories = new Application_Model_DbTable_CmsServicesCategories();
                
                $categorieas = $cmsServicesCategories->search(array(
			'filters' => array(
				'status' => Application_Model_DbTable_CmsServicesCategories::STATUS_ENABLED
			),
			'orders' => array(
				'order_number' => 'ASC'
			),
                        'limit' => 5
		));
                
                $cmsPhotosDbTable = new Application_Model_DbTable_CmsPhotos();
		
		$photos = $cmsPhotosDbTable->search(array(
			'filters' => array(
				'photo_gallery_id' => 1,
				'status' => Application_Model_DbTable_CmsPhotos::STATUS_ENABLED
			),
			'orders' => array(
				'order_number' => 'ASC'
			),
                        'limit' => 8
		));
                
		$this->view->indexSlides = $indexSlides;
                $this->view->categories = $categorieas;
                $this->view->photos = $photos;
    }
}

