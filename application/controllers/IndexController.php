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
        
                $request = $this->getRequest();
                
                $form = new Application_Form_Front_Newsletter();
                
                $flashMessenger = $this->getHelper('FlashMessenger');
		
		$systemMessages = array(
			
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors')
		);
        
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
                
                if ($request->isPost() && $request->getPost('task') === 'newsletter') {
                try {
                //check form is valid
                
                if (!$form->isValid($request->getPost())) {
                        throw new Application_Model_Exception_InvalidInput('Pogrešan unos, pokušajte ponovo');
                }
                
                //get form data
                $formData = $form->getValues();
                
                //Insert data into database
                $cmsNewsletterDbTable = new Application_Model_DbTable_CmsNewsletter();
                //Save insert to database Success
                $newsletter = $cmsNewsletterDbTable->insertNewsletter($formData);
                
                //set system message
                $flashMessenger->addMessage('Uspešno ste se prijavili za naše novosti', 'success');

                //redirect to same or another page
//                $redirector = $this->getHelper('Redirector');
//                $redirector->setExit(true)
//                        ->gotoRoute(array(
//                                'controller' => 'index',
//                                'action' => 'index'
//                        ), 'default', true);
                
                $this->_redirect($this->_request->getPost('lasturl'));
                
            } catch (Application_Model_Exception_InvalidInput $ex) {
                    //$systemMessages['errors'][] = $ex->getMessage();
                    
                    $flashMessenger->addMessage($ex->getMessage(), 'errors');

				//redirect to same or another page
				$redirector = $this->getHelper('Redirector');
				$redirector->setExit(true)
					->gotoRoute(array(
						'controller' => 'index',
						'action' => 'index'
					), 'default', true);
            }
        }
                
		$this->view->indexSlides = $indexSlides;
                $this->view->categories = $categorieas;
                $this->view->photos = $photos;
                $this->view->systemMessages = $systemMessages;
                
                
    }
}

