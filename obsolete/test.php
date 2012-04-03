<?php
//
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.email.php');

        $to = '3608521311@txt.att.net';
//        $to = 'rick@toewsweb.net';
        $from = 'signup@meocracy.com';
        $subject = 'Test from Meocracy';
        $message = 'Click here. http://www.meocracy.com/confirm.php?id=11&code=11235';
        $email = new Email($to, $from, $subject, $message);
        $result = $email->send();

print "Sent! Result: $result<br/>";
?>
