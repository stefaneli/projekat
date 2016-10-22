<?php
class AppointmentsController extends Zend_Controller_Action
{
    public function indexAction() {
        
        $request = $this->getRequest();
        
        $flashMessenger = $this->getHelper('FlashMessenger');
		
		$systemMessages = array(
			
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors')
		);


        /*         * ****** Get PhotoGalleriesPage from sitemap ****** */
        $sitemapPageId = (int) $request->getParam('sitemap_page_id');

        $this->view->activePage = $sitemapPageId;
        
        if($sitemapPageId <= 0) {
            throw new Zend_Controller_Router_Exception('Invalid sitemap page id: ' . $sitemapPageId, 404);
        }
        
        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();
        
        $sitemapPage = $cmsSitemapPageDbTable->getSitemapPageById($sitemapPageId);
        
        if(!$sitemapPage){
            throw new Zend_Controller_Router_Exception('No sitemap page is found for id: ' . $sitemapPageId, 404);
        }
        
        if(
           $sitemapPage['status'] == Application_Model_DbTable_CmsSitemapPages::STATUS_DISABLED
           //check if user is not looged in than preview is not available for displayed page
           && !Zend_Auth::getInstance()->hasIdentity()
        ) {
            throw new Zend_Controller_Router_Exception('Sitemap page is disabled.', 404);
        }
       
        
         $cmsServicesDbTable = new Application_Model_DbTable_CmsServices();
        
        $services = $cmsServicesDbTable->search(array(
            'orders' => array(
                'order_number' => 'ASC'
            ),
        ));
  
        $this->view->services = $services;
        $this->view->sitemapPage = $sitemapPage;
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
                'title' => $appointment['first_name'] . ' ' . $appointment['last_name'],
                'start' => $appointment['start'],
                'end' => $appointment['end'],
            );
        }
        
        echo json_encode($appointmentsJson);
        
    }
    

}