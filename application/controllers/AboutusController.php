<?php

class AboutusController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

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
       
        
        $cmsMembersDbTable = new Application_Model_DbTable_CmsMembers();
        
        $members = $cmsMembersDbTable->search(array(
            'filters' => array(
                'status' => Application_Model_DbTable_CmsMembers::STATUS_ENABLED
            ),
            'orders' => array(
                'order_number' => 'ASC'
            ),
//            'limit' => 4,
//            'page' => 2
        ));
        
        
        $this->view->members = $members;
        
        $this->view->sitemapPage = $sitemapPage;
        $this->view->systemMessages = $systemMessages;
    }

}
