<?php

class Admin_ServicesController extends Zend_Controller_Action
{
    public function indexAction(){
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors')
        );
        
        $cmsServicesDbTable = new Application_Model_DbTable_CmsServices();
        
        $services = $cmsServicesDbTable->search(array(
            'orders' => array(
                'order_number' => 'ASC'
            ),
        ));
  
        $this->view->services = $services;
        $this->view->systemMessages = $systemMessages;
                
    }
    
    public function addAction() {
        
        $request = $this->getRequest();
		
		$categoryId = (int) $request->getParam('service_category_id');
		
		if ($categoryId <= 0) {
			
			//prekida se izvrsavanje programa i prikazuje se "Page not found"
			throw new Zend_Controller_Router_Exception('Invalid category id: ' . $categoryId, 404);
		}
		
		$cmscategoriesTable = new Application_Model_DbTable_CmsServicesCategories();
		
		$category = $cmscategoriesTable->getServiceCategoryById($categoryId);
		
		if (empty($category)) {
			throw new Zend_Controller_Router_Exception('No category is found with id: ' . $category, 404);
		}
		
		$flashMessenger = $this->getHelper('FlashMessenger');
		
		$systemMessages = array(
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors'),
		);
		
		
		$form = new Application_Form_Admin_ServiceAdd();

		//default form data
		$form->populate(array(
			
		));

		if ($request->isPost() && $request->getPost('task') === 'save') {

			try {

				//check form is valid
				if (!$form->isValid($request->getPost())) {
					throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new service');
				}

				//get form data
				$formData = $form->getValues();
                                
                                $formData['service_category_id'] = $category['id'];
				
				//Insertujemo novi zapis u tabelu
				$cmsServicesTable = new Application_Model_DbTable_CmsServices();
				
				//insert photo returns ID of the new photo
				$serviceId = $cmsServicesTable->insertService($formData);
				
				$flashMessenger->addMessage('Service has been saved', 'success');

				//redirect to same or another page
				$redirector = $this->getHelper('Redirector');
				$redirector->setExit(true)
					->gotoRoute(array(
						'controller' => 'admin_servicescategories',
						'action' => 'edit',
						'id' => $category['id']
						), 'default', true);
			} catch (Application_Model_Exception_InvalidInput $ex) {
				
				$flashMessenger->addMessage($ex->getMessage(), 'errors');

				//redirect to same or another page
				$redirector = $this->getHelper('Redirector');
				$redirector->setExit(true)
					->gotoRoute(array(
						'controller' => 'admin_servicescategories',
						'action' => 'edit',
						'id' => $category['id']
					), 'default', true);
				
			}
		}
		
		$redirector = $this->getHelper('Redirector');
		$redirector->setExit(true)
			->gotoRoute(array(
				'controller' => 'admin_servicescategories',
                                'action' => 'edit',
                                'id' => $category['id']
			), 'default', true);
                
                
                $this->view->systemMessages = $systemMessages;
    }
    
    public function editAction() {
		
		$request = $this->getRequest();
		
		$id = (int) $request->getParam('id');
		
		if ($id <= 0) {
			
			//prekida se izvrsavanje programa i prikazuje se "Page not found"
			throw new Zend_Controller_Router_Exception('Invalid service id: ' . $id, 404);
		}
		
		$cmsServicesTable = new Application_Model_DbTable_CmsServices();
		
		$service = $cmsServicesTable->getServiceById($id);
		
		if (empty($service)) {
			throw new Zend_Controller_Router_Exception('No service is found with id: ' . $id, 404);
		}
		
		
		$flashMessenger = $this->getHelper('FlashMessenger');
		
		$systemMessages = array(
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors'),
		);
		
		
		$form = new Application_Form_Admin_ServiceAdd();

		//default form data
		$form->populate($service);

		if ($request->isPost() && $request->getPost('task') === 'update') {

			try {

				//check form is valid
				if (!$form->isValid($request->getPost())) {
					throw new Application_Model_Exception_InvalidInput('Invalid data was sent for service');
				}

				//get form data
				$formData = $form->getValues();
				
				//Radimo update postojeceg zapisa u tabeli
				
				$cmsServicesTable->updateServiceById($service['id'], $formData);
				
				//set system message
				$flashMessenger->addMessage('Service has been updated', 'success');

				//redirect to same or another page
				$redirector = $this->getHelper('Redirector');
				$redirector->setExit(true)
					->gotoRoute(array(
						'controller' => 'admin_servicescategories',
						'action' => 'edit',
						'id' => $service['service_category_id']
						), 'default', true);
			} catch (Application_Model_Exception_InvalidInput $ex) {
				$flashMessenger->addMessage($ex->getMessage(), 'errors');

				//redirect to same or another page
				$redirector = $this->getHelper('Redirector');
				$redirector->setExit(true)
					->gotoRoute(array(
						'controller' => 'admin_servicescategories',
						'action' => 'edit',
						'id' => $service['service_category_id']
					), 'default', true);
			}
		}

		$redirector = $this->getHelper('Redirector');
		$redirector->setExit(true)
			->gotoRoute(array(
				'controller' => 'admin_servicescategories',
				'action' => 'edit',
				'id' => $service['service_category_id']
			), 'default', true);
                
                $this->view->systemMessages = $systemMessages;
	}
    
    public function deleteAction() {
		
		$request = $this->getRequest();
		
		if (!$request->isPost() || $request->getPost('task') != 'delete') {
			// request is not post
			// or task is not delete
			//redirect to index page
			
			//redirect to same or another page
			$redirector = $this->getHelper('Redirector');
			$redirector->setExit(true)
				->gotoRoute(array(
					'controller' => 'admin_servicescategories',
					'action' => 'index'
					), 'default', true);
		}
		
		$flashMessenger = $this->getHelper('FlashMessenger');
                
                $systemMessages = array(
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors'),
		);
		
		try {
			
			// read $_POST['id']
			$id = (int) $request->getPost('id');

			if ($id <= 0) {
				
				throw new Application_Model_Exception_InvalidInput('Invalid service id: ' . $id);
			}

			$cmsServicesTable = new Application_Model_DbTable_CmsServices();

			$service = $cmsServicesTable->getServiceById($id);

			if (empty($service)) {
				throw new Application_Model_Exception_InvalidInput('No service is found with id: ' . $id);
			}

			$cmsServicesTable->deleteService($service);

			$flashMessenger->addMessage('Service has been deleted', 'success');

			//redirect to same or another page
			$redirector = $this->getHelper('Redirector');
			$redirector->setExit(true)
				->gotoRoute(array(
					'controller' => 'admin_servicescategories',
                                        'action' => 'edit',
                                        'id' => $service['service_category_id']
				), 'default', true);

		} catch (Application_Model_Exception_InvalidInput $ex) {
			
			$flashMessenger->addMessage($ex->getMessage(), 'errors');
			
			//redirect to same or another page
			$redirector = $this->getHelper('Redirector');
			$redirector->setExit(true)
				->gotoRoute(array(
					'controller' => 'admin_servicescategories',
					'action' => 'index'
				), 'default', true);
		}
		
                $this->view->systemMessages = $systemMessages;
	}
    
    public function disableAction() {
        
        $request = $this->getRequest();
        
        if(!$request->isPost() || $request->getPost('task') != 'disable'){
            // request is not post
            // or task is not 'delete'
            // redirect to index
            
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                ->gotoRoute(array(
                    'controller' => 'admin_servicescategories',
                    'action' => 'index'
                    ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        $systemMessages = array(
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors'),
		);
        
        try{

                // read $_POST['id']
            $id = (int) $request->getPost('id');

            if($id <= 0){
                
                throw new Application_Model_Exception_InvalidInput('Invalid service id: ' . $id);

                   }

            $cmsServicesTable = new Application_Model_DbTable_CmsServices();

            $service = $cmsServicesTable->getServiceById($id);

            if(empty($service)){
                
                throw new Application_Model_Exception_InvalidInput('No service is found with id: ' . $id);
               
            }

            $cmsServicesTable->disableService($id);

            $flashMessenger->addMessage('Service ' . $service['title'] . ' has been disabled.', 'success');

            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_servicescategories',
                        'action' => 'edit',
                        'id' => $service['service_category_id']
                        ), 'default', true);


        } catch (Application_Model_Exception_InvalidInput $ex) {
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
             $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_servicescategories',
                        'action' => 'index'
                        ), 'default', true);
        }
        
        $this->view->systemMessages = $systemMessages;
        
    }
    
    public function enableAction() {
        
        $request = $this->getRequest();
        
        if(!$request->isPost() || $request->getPost('task') != 'enable'){
            // request is not post
            // or task is not 'delete'
            // redirect to index
            
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                ->gotoRoute(array(
                    'controller' => 'admin_servicescategories',
                        'action' => 'index'
                    ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        $systemMessages = array(
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors'),
		);
        
        try{

                // read $_POST['id']
            $id = (int) $request->getPost('id');

            if($id <= 0){
                
                throw new Application_Model_Exception_InvalidInput('Invalid service id: ' . $id);

                   }

            $cmsServicesTable = new Application_Model_DbTable_CmsServices();

            $service = $cmsServicesTable->getServiceById($id);

            if(empty($service)){
                
                throw new Application_Model_Exception_InvalidInput('No service is found with id: ' . $id);
               
            }

            $cmsServicesTable->enableService($id);

            $flashMessenger->addMessage('Service ' . $service['title'] . ' has been enabled.', 'success');

            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_servicescategories',
                        'action' => 'edit',
                        'id' => $service['service_category_id']
                        ), 'default', true);


        } catch (Application_Model_Exception_InvalidInput $ex) {
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
             $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_servicescategories',
                        'action' => 'index',
                        ), 'default', true);
        }
        
        $this->view->systemMessages = $systemMessages;
    }
    
    public function updateorderAction() {
       
        $request = $this->getRequest();
        
        $serviceCategoryId = (int) $request->getParam('service_category_id');
        
        if ($serviceCategoryId <= 0) {
			
			//prekida se izvrsavanje programa i prikazuje se "Page not found"
			throw new Zend_Controller_Router_Exception('Invalid category id: ' . $serviceCategoryId, 404);
		}
                
        $cmsServicesCategoriesTable = new Application_Model_DbTable_CmsServicesCategories();
        
        $category = $cmsServicesCategoriesTable->getServiceCategoryById($serviceCategoryId);
        
        if (empty($category)) {
			throw new Zend_Controller_Router_Exception('No category is found with id: ' . $serviceCategoryId, 404);
		}
        
        if(!$request->isPost() || $request->getPost('task') != 'saveOrder'){
            // request is not post
            // or task is not 'delete'
            // redirect to index
            
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                ->gotoRoute(array(
                    'controller' => 'admin_servicescategories',
                    'action' => 'index'
                    ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        $systemMessages = array(
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors'),
		);
        
        try{
            
            $sortedIds = $request->getPost('sorted_ids');
            
            if(empty($sortedIds)) {
                throw Application_Model_Exception_InvalidInput('Sorted ids are not sent');
            }
            
            $sortedIds = trim($sortedIds, ' ,');
            
            if(!preg_match('/^[0-9]+(,[0-9]+)*$/', $sortedIds)) {
                throw Application_Model_Exception_InvalidInput('Invalid sorted ids: ' . $sortedIds);
            }
            
            $sortedIds = explode(',', $sortedIds);
            
            $cmsServiceTable = new Application_Model_DbTable_CmsServices();
            
            $cmsServiceTable->updateServiceOrder($sortedIds);
            
            $flashMessenger->addMessage('Order is successfuly saved', 'success');
            
             $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_servicescategories',
                        'action' => 'edit',
                        'id' => $category['id']
                        ), 'default', true);
            
        } catch (Application_Model_Exception_InvalidInput $ex) {
             $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
             $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_servicescategories',
                        'action' => 'index'
                        ), 'default', true);
        }
        
        $this->view->systemMessages = $systemMessages;
    }
    
    public function dashboardAction() {
        
            $cmsServicesDbTable = new Application_Model_DbTable_CmsServices();
            
            $total = $cmsServicesDbTable->count();
        
            $active = $cmsServicesDbTable->count(array(
                'status' => Application_Model_DbTable_CmsMembers::STATUS_ENABLED
            ));
        
         
            $this->view->total = $total;
            $this->view->active = $active;
        
      
    }

}
