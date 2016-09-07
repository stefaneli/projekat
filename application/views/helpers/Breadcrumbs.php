<?php

class Zend_View_Helper_Breadcrumbs extends Zend_View_Helper_Abstract
{
    public function breadcrumbs($sitemapPage){
        
        $sitemapPagesBreadcrumbs = array();
        
        $sitemapPagesBreadcrumbs[] = $sitemapPage;
        
        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();
        
        
        
        if($sitemapPage['parent_id'] != 0) {
            
            $sitemapPage = $cmsSitemapPageDbTable->getSitemapPageById($sitemapPage['parent_id']);
            
            array_shift($sitemapPagesBreadcrumbs, $sitemapPage);
            
            if($sitemapPage['parent_id'] != 0) {
                
                $sitemapPage = $cmsSitemapPageDbTable->getSitemapPageById($sitemapPage['parent_id']);
                
                array_shift($sitemapPagesBreadcrumbs, $sitemapPage);
            }
        }
        
        // Resetovanje placeholdera
        $this->view->placeholder('breadcrumbs')->exchangeArray(array());
        $this->view->placeholder('breadcrumbs')->captureStart(); 
        
        ?>
        
        <ul class="breadcrumbs">
            
        <li><a href="<?php echo $this->view->baseUrl('/'); ?>">Naslovna Strana</a></li>
        
            <?php 
                $i = 1;
                
                foreach ($sitemapPagesBreadcrumbs as $sitemapP) { 
                
                if($i == count($sitemapPagesBreadcrumbs)) {
                ?>

                <li><?php echo $sitemapP['title']; ?></li>
            
                <?php } else { ?>
                    
                  <li><a href="<?php echo $this->view->sitemapPageUrl($sitemapP['id']); ?>"><?php echo $sitemapP['title']; ?></a></li>  
                    
             <?php $i++;  } ?>
            
            
       <?php }?>
                   
        </ul>
        
       <?php
       $this->view->placeholder('breadcrumbs')->captureEnd();
       
       return $this->view->placeholder('breadcrumbs')->toString();
    }
}