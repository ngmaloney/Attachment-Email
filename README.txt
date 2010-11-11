================================================================================
  DESCRIPTION
================================================================================
AttachmentEmail is a simple php class used for sending emails with attachments.
It is a modified version of a script found at
(http://www.kavoir.com/2009/08/php-email-attachment-class.html). While it is
platform agnostic,  it was developed to get around the complexities of sending
an email with attachments in Drupal 7. The primary modules for doing so haven't
yet been ported from D6.

================================================================================
  USAGE
================================================================================

PHP Example

<?php

//The following code is for example only.

require_once('attachment_email.php');

$to="ish.williams1949@gmail.com";
$from="ngmaloney@gmail.com";
$subject="Check out this report!";
$message="Yo! Check out this report!";
$attachment = array(
  'url' => '~/documents/report.pdf',
  'filename' => 'report.pdf',
);

$email = new AttachmentEmail($to, $from, $subject, $message, $attachment);
$email->send();

?>

Drupal 7 Example (partial)

mymodule_form($form, &$form_state) {
  $form['to'] = array(
    '#type' => 'textfield',
    '#title' => t('To'),
  );
  $form['from'] = array(
    '#type' => 'textfield',
    '#title' => t('From'),
  );
  $form['message'] = array(
    '#type' => 'textarea',
    '#title' => t('Message'),
  );
  $form['file'] = array(
    '#type' => 'file',
    '#title' => t('Attachment'),
  );
  $form['send'] = array(
    '#type' => 'submit',
    '#value' => t('Send'),
  );
}

mymodule_form_submit($form, &$form_state) {
  //Include Attachment Emailer
  module_load_include('php','mymodule', 'attachment_email');

  $to = $form_state['values']['to'];
  $from = $form_state['values']['from'];
  $message = $form_state['values']['message'];
  $attachment = file_save_upload('attachment');

  //Send Email!
  $email = new AttachmentEmail($to, $from, $message, $body);
  $email->send();

  //perform any addition logic....

}


