<?php
class Admin_AppointmentsController extends Zend_Controller_Action
{
    public function indexAction() {
        
         $cmsServicesDbTable = new Application_Model_DbTable_CmsServices();
        
        $services = $cmsServicesDbTable->search(array(
            'orders' => array(
                'order_number' => 'ASC'
            ),
        ));
  
        $this->view->services = $services;
    }
    
    public function calendarAction() {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        
        $cmsAppDbTable = new Application_Model_DbTable_CmsAppointments();
        
        $appointments = $cmsAppDbTable->search(array(
            'filters' => array(
                'start' => $this->getParam('start')." 00:00:00",
                'end' => $this->getParam('end')." 23:59:00"
            )
        ));
        
        $appointmentsJson = array();
        
        foreach ($appointments as $key => $appointment) {
            $appointmentsJson[$key] = array(
                "allDay" => "",
                'id' => $appointment['id'],
                'title' => $appointment['first_name'] . ' ' . $appointment['last_name'] . ' - '. $appointment['service'] . ' - ' . $appointment['phone'],
                'start' => $appointment['start'],
                'end' => $appointment['end'],
            );
        }
        
        echo json_encode($appointmentsJson);
        
    }
    
    public function addAction() {
        
        $cmsAppDbTable = new Application_Model_DbTable_CmsAppointments();
        echo $date = $this->getParam('date_hidden');
        echo $start = date('Y-m-d H:i:s', strtotime($date));
        echo $end = date('Y-m-d H:i:s', strtotime($date));
        
//        $first_name = $this->getParam('first_name');
//        $last_name = $this->getParam('last_name');
//        $phone = $this->getParam('phone');
//        
        $appointment = array(
            'start' => date('Y-m-d H:i:s', strtotime($date)),
            'end' => date('Y-m-d H:i:s', strtotime($date)+3600),
            'first_name' => $this->getParam('first_name'),
            'last_name' => $this->getParam('last_name'),
            'service' => $this->getParam('service'),
            'phone' => $this->getParam('phone')
        );
        
        $cmsAppDbTable->insertAppointment($appointment);
        
        $this->redirect('/admin_appointments');
        
    }
   
    public function deleteAction(){
        
        $request = $this->getRequest();
        
        if(!$request->isPost() || $request->getPost('task') != 'delete'){
            // request is not post
            // or task is not 'delete'
            // redirect to index
            
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                ->gotoRoute(array(
                    'controller' => 'admin_appointments',
                    'action' => 'index'
                    ), 'default', true);
        }
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        try{

                // read $_POST['id']
            $id = (int) $request->getPost('id');

            if($id <= 0){
                
                throw new Application_Model_Exception_InvalidInput('Invalid appointment id: ' . $id);

                   }

            $cmsAppDbTable = new Application_Model_DbTable_CmsAppointments();

            $appointment = $cmsAppDbTable->getAppointmentById($id);

            if(empty($appointment)){
                
                throw new Application_Model_Exception_InvalidInput('No appointment is found with id: ' . $id);
               
            }

            $cmsAppDbTable->deleteAppointment($id);

           
            $flashMessenger->addMessage('Appointemnt has been deleted.', 'success');

            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_appointments',
                        'action' => 'index'
                        ), 'default', true);


        } catch (Application_Model_Exception_InvalidInput $ex) {
            $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
             $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_appointments',
                        'action' => 'index'
                        ), 'default', true);
        } 
        
        
        
    }
    
   
    
    public function dashboardAction() {
        
            $cmsUsersDbTable = new Application_Model_DbTable_CmsUsers();
            
            $total = $cmsUsersDbTable->count();
        
            $active = $cmsUsersDbTable->count($filters = array('status' => Application_Model_DbTable_CmsUsers::STATUS_ENABLED));
        
         
            $this->view->total = $total;
            $this->view->active = $active;
        
      
    }
    
}