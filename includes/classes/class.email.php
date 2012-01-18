<?php
class Email 
{
    private $_from;
    private $_to;
    private $_cc;
    private $_bcc;
    private $_subject;
    private $_message;
    private $_attachment;
    private $_format;

    function __construct($to = null, $from = null, $subject = null, $message = null)
    {
        $this->set_to($to);
        $this->set_from($from);
        $this->set_subject($subject);
        $this->set_message($message);
    }

    public static function format_opinion_message($response, $comments)
    {
        $message = '';
        if ($response == 1) {
            $message = 'Support';
        }
        elseif ($response == -1) {
            $message = 'Oppose';
        }
            
        if ($message && $comments) {
            $message .= "\n\n$comments";
        }

        return $message;
    }



    function set_from($from) {
        $this->_from = $from;
    }

    function set_to($to) {
        $this->_to = $to;
    }

    function set_cc($cc) {
        $this->_cc = $cc;
    }

    function set_bcc($bcc) {
        $this->_bcc = $bcc;
    }

    function set_subject($subject) {
        $this->_subject = $subject;
    }

    function set_message($message) {
        $this->_message = $message;
    }

    function set_format($format) {
        if ($format == "H") {
            $this->_format = "text/html";
        }
        else {
            $this->_format = "text/plain";
        }
    }

    function add_attachment($file) {
        $this->_attachment[] = $file;
    }

    function send() {
        $headers  = "From: $this->from\r\n";
        if ($this->_cc) {
            $headers .= "cc: $this->cc\r\n";
        }
        if ($this->_bcc) {
            $headers .= "bcc: $this->bcc\r\n";
        }
        if (!empty($this->_attachment)) {
            $mime_boundary = "<<<--==+X[" . md5(time()) . "]";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: multipart/mixed;\r\n";
            $headers .= " boundary=\"$mime_boundary\"\r\n";

            $headers .= "--" . $mime_boundary . "\r\n";
            $headers .= "Content-type: $this->_format\r\n";
            $headers .= "Content-Transfer-Encoding: 8bit\r\n\n";
            $headers .= "$this->_message\n";

            foreach($this->_attachment as $n => $a) {
                if (preg_match("/\/([^\/]+)$/", $a, $match)) {
                    $filename = $match[1];
                }
                else {
                    $filename = $a;
                }
                $fp = fopen($a, "r");
                $content = "";
                while (!feof($fp)) {
                    $content .= fread($fp, 1024);
                }
                fclose($fp);
                $this->content = $content;

                $encoded_content = chunk_split(base64_encode($this->content));

                $headers .= "\r\n";
                $headers .= "--" . $mime_boundary . "\r\n";
                $headers .= "Content-Type: application/octet-stream; ";
                $headers .= " name=\"$filename\"\r\n";
                $headers .= "Content-Transfer-Encoding: base64\r\n";
                $headers .= "Content-Disposition: attachment; ";
                $headers .= "filename=\$filename\"\r\n\n";
                $headers .= "$encoded_content\r\n";
            }
            $headers .= "--" . $mime_boundary . "--";
        }
        else {
            $headers .= "Content-type: $this->_format\r\n";
        }

        $result = mail($this->_to, $this->_subject, $this->_message, $headers); 
        return $result;
    }
}
?>
