<?php

/**
 * @package    opNewsletterPlugin
 * @subpackage form
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
class opNewsletterSendForm extends BaseForm
{
  public function configure()
  {
    $this->setWidget('subject', new sfWidgetFormInput());
    $this->setValidator('subject', new sfValidatorPass());

    $this->setWidget('body', new sfWidgetFormTextarea());
    $this->setValidator('body', new sfValidatorPass());

    $this->widgetSchema->setNameFormat('newsletter[%s]');
  }

  public function sendMail()
  {
    if (!$this->isValid())
    {
      throw new LogicException('The form is not valid.');
    }

    $subject = $this->values['subject'];
    $fromMailAddress = opConfig::get('admin_mail_address');
    $body = $this->values['body'];

    $signature = opMailSend::getMailTemplate('signature', 'pc');
    if ('' !== $signature)
    {
      $body .= "\n\n".$signature;
    }

    $subscribers = NewsletterSubscriberTable::getInstance()->findAll();
    foreach ($subscribers as $subscriber)
    {
      $toMailAddress = $subscriber->mail_address;
      opMailSend::execute($subject, $toMailAddress, $fromMailAddress, $body);

      $subscriber->free(true);
    }
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
