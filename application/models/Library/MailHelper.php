<?php

class Application_Model_Library_MailHelper
{
    public function sendMail($toEmail, $fromEmail, $fromName, $message, $subject) {
        
        $mail = new Zend_Mail('UTF-8');
        $mail->setSubject('Poruka sa kontakt forme - ' . $subject);
        $mail->addTo($toEmail);
        $mail->setFrom($fromEmail, $fromName);
        $mail->setBodyHtml($message);
        $mail->setBodyText($message);
       
        return $result =  $mail->send();;
    }
    
}