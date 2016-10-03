<?php
class Admin_AppointmentsController extends Zend_Controller_Action
{
    public function indexAction() {
        
        $cmsAppDbTable = new Application_Model_DbTable_CmsAppointments();
        
        $appointments = $cmsAppDbTable->search(array(
//            'filters' => array(
//                'first_name' => array('Aleksandar', 'Aleksandra', 'Bojan')
//            ),
            'orders' => array(
                'order_number' => 'ASC'
            ),
//            'limit' => 4,
//            'page' => 2
        ));
        
        $app = json_encode($appointments);
        
        $this->view->app = $app;
        
    }
    
    public function calendarAction() {
        
        $cmsAppDbTable = new Application_Model_DbTable_CmsAppointments();
        
//        $appointments = $cmsAppDbTable->search(array(
////            'filters' => array(
////                'first_name' => array('Aleksandar', 'Aleksandra', 'Bojan')
////            ),
//            'orders' => array(
//                'order_number' => 'ASC'
//            ),
////            'limit' => 4,
////            'page' => 2
//        ));
        
        $appointments = $cmsAppDbTable->getAllAppointment();
        
        $appointmentsJson = array();
        
        foreach ($appointments as $appointment) {
            $appointmentsJson[] = array(
                'id' => $appointment['id'],
                'title' => $appointment['last_name'],
                'start' => $appointment['appointment_date_time']
            );
        }
        
        $this->getHelper('Json')->sendJson($appointmentsJson);
        
    }
    
    public function addAction() {
        
    }
    
    public function editAction() {
        
    }
    
    public function deleteAction(){
        
    }
    
   
    
    public function dashboardAction() {
        
            $cmsUsersDbTable = new Application_Model_DbTable_CmsUsers();
            
            $total = $cmsUsersDbTable->count();
        
            $active = $cmsUsersDbTable->count($filters = array('status' => Application_Model_DbTable_CmsUsers::STATUS_ENABLED));
        
         
            $this->view->total = $total;
            $this->view->active = $active;
        
      
    }
    
}