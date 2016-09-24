<?php

class Zend_View_Helper_Newsletter extends Zend_View_Helper_Abstract
{
    public function newsletter() { 
//        
//        $request = $this->getRequest();
//        
//       $sitemapPageId = $request->getParam('sitemap_page_id');
//        
//        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();
//        
//        $sitemapPage = $cmsSitemapPageDbTable->getSitemapPageById($sitemapPageId);
//        
//        if($sitemapPage['type'] != 'ContactPage') {
//        
       ?>
    

                                                                        
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->view->systemMessagesFront($this->view->systemMessages);?>
            </div>
        </div>


      

        <form method="post" id="subscribe-form" action="<?php echo $this->view->url(array('controller' => 'index', 'action' => 'index'), 'default', true);?>" >
                <input type="hidden" name="task" value="newsletter">
                <input type="hidden" name="lasturl" value="<?php echo Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();?>">
<!--                 VALIDATION ERROR MESSAGE : begin 
                <p style="display: none;" class="c-alert-message m-warning m-validation-error"><i class="ico fa fa-exclamation-circle"></i>Email adresa je obavezna.</p>
                 VALIDATION ERROR MESSAGE : end 

                 SENDING REQUEST ERROR MESSAGE : begin 
                <p style="display: none;" class="c-alert-message m-warning m-request-error"><i class="ico fa fa-exclamation-circle"></i>There was a connection problem. Try again later.</p>
                 SENDING REQUEST ERROR MESSAGE : end 

                 SUCCESS MESSAGE : begin 
                <p style="display: none;" class="c-alert-message m-success"><i class="ico fa fa-check-circle"></i><strong>Uspešno ste se prijavili za naše novosti!</strong></p>
                 SUCCESS MESSAGE : end -->

                <div class="form-fields">
                    <input class="m-required m-email" data-placeholder="Vaša e-mail adresa" name="email">
                    <button class="c-button submit-btn"  data-label="Subscribe"
                    onclick="document.getElementById('subscribe-form').submit();">Prijavi se</button>
                </div>
        </form>

    <?php
    }
}
