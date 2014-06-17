<?php

/**
 * @package    opNewsletterPlugin
 * @subpackage form
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
class opNewsletterSubscriberManageForm extends BaseForm
{
  public function configure()
  {
    $this->setWidget('mail_address', new sfWidgetFormTextarea());
    $this->setValidator('mail_address', new sfValidatorCallback(array(
      'callback' => array($this, 'validateMailAddress'),
    )));

    $this->widgetSchema->setNameFormat('newsletter_subscriber[%s]');
  }

  public function validateMailAddress(sfValidatorCallback $validator, $value, array $arguments)
  {
    $validatorEmail = new sfValidatorEmail(array(
      'required' => false,
      'empty_value' => null,
      'trim' => true,
    ));

    // textarea に入力された複数件のメールアドレスをバリデーションした後、配列として整形する
    $cleanMailAddress = array();

    foreach (explode("\n", $value) as $line)
    {
      $clean = $validatorEmail->clean($line);

      if (null === $clean)
      {
        continue;
      }

      $cleanMailAddress[] = $clean;
    }

    return $cleanMailAddress;
  }

  public function doSubscribeAll()
  {
    if (!$this->isValid())
    {
      throw new LogicException('The form is not valid.');
    }

    $sendMail = $this->getOption('sendMail', true);

    NewsletterSubscriberTable::getInstance()
      ->subscribeAll($this->values['mail_address'], $sendMail);
  }

  public function doUnsubscribeAll()
  {
    if (!$this->isValid())
    {
      throw new LogicException('The form is not valid.');
    }

    $sendMail = $this->getOption('sendMail', true);

    NewsletterSubscriberTable::getInstance()
      ->unsubscribeAll($this->values['mail_address'], $sendMail);
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
