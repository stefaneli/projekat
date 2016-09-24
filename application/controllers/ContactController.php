<?php


class ContactController extends Zend_Controller_Action
{
    

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        
        $form = new Application_Form_Front_Contact();

        //default form data
        $form->populate(array(
            
        ));
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        $systemMessages = array(

                'success' => $flashMessenger->getMessages('success'),
                'errors' => $flashMessenger->getMessages('errors')
        );
        
        $systemMessagesForContact = 'init';
        
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
        
        if ($request->isPost() && $request->getPost('task') === 'contact') {
	
	try {
		
		//check form is valid
		if (!$form->isValid($request->getPost())) {
			throw new Application_Model_Exception_InvalidInput('Invalid form data was sent');
		}
		
		//get form data
		$formData = $form->getValues();
		
		$mailHelper = new Application_Model_Library_MailHelper();
                
                $fromEmail = $formData['email'];
                $toEmail = 'bebeautymirijevo@gmail.com';
                $fromName = $formData['first_name'];
                $message = $formData['message'];
                $subject = $formData['subject'];
                
                
                $result = $mailHelper->sendMail($toEmail, $fromEmail, $fromName, $message, $subject);
                
                if(!$result) {
                    $systemMessagesForContact = 'Error';
                } else {
                    $systemMessagesForContact = 'Success';
                }
                
		
		
		
	} catch (Application_Model_Exception_InvalidInput $ex) {
		$systemMessages['errors'][] = $ex->getMessage();
	}
}

        
        
          
        $this->view->sitemapPage = $sitemapPage;
        
        $this->view->systemMessagesForContact = $systemMessagesForContact;
        $this->view->systemMessages = $systemMessages;
        
        $this->view->form = $form;
    }
    
    


}

