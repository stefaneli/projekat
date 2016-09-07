<?php

class ServicesController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
      $request = $this->getRequest();


        /*         * ****** Get PhotoGalleriesPage from sitemap ****** */
        $sitemapPageId = (int) $request->getParam('sitemap_page_id');

        $this->view->activePage = $sitemapPageId;
        
    }


}

