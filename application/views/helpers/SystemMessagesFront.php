<?php

class Zend_View_Helper_SystemMessagesFront extends Zend_View_Helper_Abstract
{
	public function systemMessagesFront($systemMessages) {
		
		//resetovanje placeholder-a
		$this->view->placeholder('systemMessagesFront')->exchangeArray(array());
		
		if (!empty($systemMessages['success'])) {
			
			foreach ($systemMessages['success'] as $message) {
				
				$this->view->placeholder('systemMessagesFront')->captureStart();
				?>
                                <p class="c-alert-message m-success"><i class="ico fa fa-check-circle"></i><?php echo $this->view->escape($message);?></p>
				
				<?php
				$this->view->placeholder('systemMessagesFront')->captureEnd();
			}
		}
		
		if (!empty($systemMessages['errors'])) {
			
			foreach ($systemMessages['errors'] as $message) {
				
				$this->view->placeholder('systemMessagesFront')->captureStart();
				?>
                                <p class="c-alert-message m-warning"><i class="ico fa fa-exclamation-circle"></i><?php echo $this->view->escape($message);?></p>
				
				<?php
				$this->view->placeholder('systemMessagesFront')->captureEnd();
			}
		}
		
		
		return $this->view->placeholder('systemMessagesFront')->toString();
	}
}