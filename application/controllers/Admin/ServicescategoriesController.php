<?php

class Admin_ServicescategoriesController extends Zend_Controller_Action
{
    public function indexAction(){
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors')
        );
        
        
        $cmsServicesCategoryDbTable = new Application_Model_DbTable_CmsServicesCategories();
        
        $categories = $cmsServicesCategoryDbTable->search(array(
            'orders' => array(
                'order_number' => 'ASC'
            ),
        ));
  
        $this->view->categories = $categories;
        $this->view->systemMessages = $systemMessages;
                
    }
    
    public function addAction() {
        
       $request = $this->getRequest();
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
          $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors')
        );

        $form = new Application_Form_Admin_ServiceCategoryAdd();

        //default form data
        $form->populate(array(
            
        ));

      

        if ($request->isPost() && $request->getPost('task') === 'save') {

            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new category.');
                }

                //get form data
                $formData = $form->getValues();
                
                // remove key photo_gallery_leading_photo from data because there is no column 'photo_gallery_leading_photo' in cms_photoGalleries table
                unset($formData['category_leading_photo']);
                // do actual task
                //save to database etc
             
                 // Insertujemo novi zapis u tabelu
                $cmsCategoriesTable = new Application_Model_DbTable_CmsServicesCategories();
                
                // insert photoGallery returns ID of the new photoGallery
                $categoryId = $cmsCategoriesTable->insertServiceCategory($formData);
                
                
                
                if($form->getElement('category_leading_photo')->isUploaded()){
                
                    // photo is uploaded
                    
                    $fileInfos = $form->getElement('category_leading_photo')->getFileInfo('category_leading_photo');
                    $fileInfo = $fileInfos['category_leading_photo'];
                    // $fileInfo = $_FILES['photoGallery_phpto'];
                    
                    try{
                        // Open uploaded photo in temporary directory
                        $categoryPhoto = Intervention\Image\ImageManagerStatic::make($fileInfo['tmp_name']);
                        
                        $categoryPhoto->fit(360, 270);
                        
                        $categoryPhoto->save(PUBLIC_PATH . '/uploads/usluge-salon-lepote-mirijevo/salon-lepote-mirijevo-' . $categoryId . '.jpg');
                        
                        
                        
                    } catch (Exception $ex) {
                        $flashMessenger->addMessage('Category has been saved, but error ocured during image processing', 'errors');

                        //redirect to same or another page
                        $redirector = $this->getHelper('Redirector');
                        $redirector->setExit(true)
                                ->gotoRoute(array(
                                    'controller' => 'admin_servicescategories',
                                    'action' => 'edit',
                                    'id' => $categoryId
                                        ), 'default', true);
                    }
                    
                } 
                
                //set system message
                $flashMessenger->addMessage('Category has been saved', 'success');

                //redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_servicescategories',
                            'action' => 'edit',
                            'id' => $categoryId
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;
    }
    
    public function editAction() {
        
        $request = $this->getRequest();
        
        $id = (int) $request->getParam('id');
        
        if($id <= 0){
            
            // Prekida se izvrsavanje programa i prikazuje se page not found
            throw  new Zend_Controller_Router_Exception('Invalid category id: ' . $id, 404);
        }
        
        $cmsCategoriesTable = new Application_Model_DbTable_CmsServicesCategories();
        
        $category = $cmsCategoriesTable->getServiceCategoryById($id);
        
        if(empty($category)){
            throw new Zend_Controller_Router_Exception('No category is found with id: ' . $id, 404);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors')
        );

        $form = new Application_Form_Admin_ServiceCategoryEdit();

        //default form data
        $form->populate($category);

      

        if ($request->isPost() && $request->getPost('task') === 'update') {

            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for category.');
                }

                //get form data
                $formData = $form->getValues();
                
                // do actual task
                //save to database etc
             
                 // Update postojeceg zapisa u tabeli
                
                unset($formData['category_leading_photo']);
                
                if($form->getElement('category_leading_photo')->isUploaded()){
                
                    // photo is uploaded
                    
                    $fileInfos = $form->getElement('category_leading_photo')->getFileInfo('category_leading_photo');
                    $fileInfo = $fileInfos['category_leading_photo'];
                    // $fileInfo = $_FILES['photoGallery_phpto'];
                    
                    try{
                        // Open uploaded photo in temporary directory
                        $categoryPhoto = Intervention\Image\ImageManagerStatic::make($fileInfo['tmp_name']);
                        
                        $categoryPhoto->fit(360, 270);
                        
                        $categoryPhoto->save(PUBLIC_PATH . '/uploads/categories/' . $category['id'] . '.jpg');
                        
                        
                    } catch (Exception $ex) {
                        
                        throw new Application_Model_Exception_InvalidInput('Error ocured during image processing');
                        
                    }
                    
                }
                
                $cmsCategoriesTable->updateServiceCategoryById($category['id'], $formData);
                
                
                
                
                //set system message
                $flashMessenger->addMessage('Category has been updated', 'success');

                //redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_servicescategories',
                            'action' => 'index'
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $cmsServicesDbTable = new Application_Model_DbTable_CmsServices();
        
        $services = $cmsServicesDbTable->search(array(
            'filters' => array(
                'service_category_id' => $category['id']
            ),
            'orders' => array(
                'order_number' => 'ASC'
            )
        ));
        
        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;
        
        $this->view->category = $category;
        $this->view->services = $services;
        
        
    }
    
    public function deleteAction() {
        
        $request = $this->getRequest();
        
        if(!$request->isPost() || $request->getPost('task') != 'delete'){
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
        
        try{

                // read $_POST['id']
            $id = (int) $request->getPost('id');

            if($id <= 0){
                
                throw new Application_Model_Exception_InvalidInput('Invalid category id: ' . $id);

                   }

            $cmsCategoriesTable = new Application_Model_DbTable_CmsServicesCategories();

            $category = $cmsCategoriesTable->getServiceCategoryById($id);

            if(empty($category)){
                
                throw new Application_Model_Exception_InvalidInput('No category is found with id: ' . $id);
               
            }

            $cmsCategoriesTable->deleteServiceCategory($category);

            
            // Brisanje slike za obrisanog photoGallerya
            $categoryFilePath = PUBLIC_PATH . '/uploads/categories/' . $category['id'] . '.jpg';
            
            if(is_file($categoryFilePath)){
                unlink($categoryFilePath);
            }
            
            
            $flashMessenger->addMessage('Category ' . $category['tiltel'] . ' has been deleted.', 'success');

            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_servicescategories',
                        'action' => 'index'
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
        
        try{

                // read $_POST['id']
            $id = (int) $request->getPost('id');

            if($id <= 0){
                
                throw new Application_Model_Exception_InvalidInput('Invalid category id: ' . $id);

                   }

            $cmsCategoriesTable = new Application_Model_DbTable_CmsServicesCategories();

            $category = $cmsCategoriesTable->getServiceCategoryById($id);

            if(empty($category)){
                
                throw new Application_Model_Exception_InvalidInput('No category is found with id: ' . $id);
               
            }

            $cmsCategoriesTable->disableServiceCategory($id);

            $flashMessenger->addMessage('Category ' . $category['title'] . ' has been disabled.', 'success');

            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_servicescategories',
                        'action' => 'index'
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
        
        try{

                // read $_POST['id']
            $id = (int) $request->getPost('id');

            if($id <= 0){
                
                throw new Application_Model_Exception_InvalidInput('Invalid category id: ' . $id);

                   }

            $cmsCategoriesTable = new Application_Model_DbTable_CmsServicesCategories();

            $category = $cmsCategoriesTable->getServiceCategoryById($id);

            if(empty($category)){
                
                throw new Application_Model_Exception_InvalidInput('No category is found with id: ' . $id);
               
            }

            $cmsCategoriesTable->enableServiceCategory($id);

            $flashMessenger->addMessage('Category ' . $category['title'] . ' has been disabled.', 'success');

            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_servicescategories',
                        'action' => 'index'
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
    }
    
    public function updateorderAction() {
       
        $request = $this->getRequest();
        
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
            
            $cmsCategoriesTable = new Application_Model_DbTable_CmsServicesCategories();
            
            $cmsCategoriesTable->updateServiceCategoryOrder($sortedIds);
            
            $flashMessenger->addMessage('Order is successfuly saved', 'success');
            
             $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_servicescategories',
                        'action' => 'index'
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
