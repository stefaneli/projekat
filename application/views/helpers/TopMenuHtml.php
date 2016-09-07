<?php

class Zend_View_Helper_TopMenuHtml extends Zend_View_Helper_Abstract
{
    public function topMenuHtml(){
        
        
        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();
        
        $topMenuSitemapPages = $cmsSitemapPageDbTable->search(array(
            'filters' => array(
                'parent_id' => 0,
                'status' => Application_Model_DbTable_CmsSitemapPages::STATUS_ENABLED
            ),
            'orders' => array(
                'order_number' => 'ASC'
            )
        ));
        
        // Resetovanje placeholdera
        $this->view->placeholder('topMenuHtml')->exchangeArray(array());
        $this->view->placeholder('topMenuHtml')->captureStart(); 
        $activePage = $this->view->activePage;
        
        ?>

                        <?php foreach($topMenuSitemapPages as $sitemapPage)  {?>
                        <li <?php echo ($activePage == $sitemapPage['id']) ? 'class="m-active"' : '';  ?>>
                            <span> <a href="<?php echo $this->view->sitemapPageUrl($sitemapPage['id']); ?>"><?php echo $this->view->escape($sitemapPage['short_title']); ?></a></span>
                        </li>    
                       <?php }
                       ?>

                   
        
        
       <?php
       $this->view->placeholder('topMenuHtml')->captureEnd();
       
       return $this->view->placeholder('topMenuHtml')->toString();
    }
}