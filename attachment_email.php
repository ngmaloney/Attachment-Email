<?php
/**
 * @File a mime mail attachment class used for sending emails with attachments.
 *
 * Modified from a version created at http://www.kavoir.com/2009/08/php-email-attachment-class.html
**/
class AttachmentEmail {
	private $from = '';
	private $from_name = '';
	private $reply_to = '';
	private $to = '';
	private $subject = '';
	private $message = '';
	private $attachment = '';
	private $attachment_filename = '';

  /**
   * Function for defining email
   * @param $to
   *    Email recipient address
   * @param $from
   *    Email sender address
   * @param $message
   *    Message text of body. (currenlty plain text only)
   * $param $attachment (optional)
   *    An array containing attachment file name and path
   *    array('filename' => 'attachment.pdf', 'uri' => '/tmp/attachment.pdf')
 **/
  public function __construct($to, $from, $subject, $message, $attachment =
  array()) {
		$this->to = $to;
    $this->from = $from;
		$this->subject = $subject;
		$this->message = $message;
		$this->attachment = $attachment['uri'];
		$this->attachment_filename = $attachment['filename'];
	}

  /**
   * Hook for sending actual eamil
  **/
  public function send() {
		if (!empty($this -> attachment)) {
			$filename = empty($this -> attachment_filename) ? basename($this -> attachment) : $this -> attachment_filename ;
			$path = dirname($this -> attachment);
			$mailto = $this -> to;
			$from_mail = $this -> from;
			$from_name = $this -> from_name;
			$replyto = $this -> reply_to;
			$subject = $this -> subject;
			$message = $this -> message;

      $content = file_get_contents($this->attachment);
			$content = chunk_split(base64_encode($content));
			$uid = md5(uniqid(time()));
			$header = "From: " . $from_mail . "\r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
			$header .= "This is a multi-part message in MIME format.\r\n";
			$header .= "--".$uid."\r\n";
			$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
			$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
			$header .= $message."\r\n\r\n";
			$header .= "--".$uid."\r\n";
			$header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use diff. tyoes here
			$header .= "Content-Transfer-Encoding: base64\r\n";
			$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
			$header .= $content."\r\n\r\n";
			$header .= "--".$uid."--";
			if (mail($mailto, $subject, "", $header)) {
				return true;
			}
      else {
				return false;
			}
		}
    else {
			$header = "From: ".($this -> from_name)." <".($this -> from).">\r\n";
			$header .= "Reply-To: ".($this -> reply_to)."\r\n";

			if (mail($this -> to, $this -> subject, $this -> message, $header)) {
				return true;
			} else {

				return false;
			}

		}
	}
}

