<?php

/**
 * @package    opNewsletterPlugin
 * @subpackage form
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
class opNewsletterSubscriptionForm extends NewsletterSubscriberForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->disableLocalCSRFProtection();
    $this->useFields(array('mail_address'));

    // ユニーク制約のチェックを無効化
    $this->validatorSchema->setPostValidator(new sfValidatorPass());
  }

  public function doSubscribe()
  {
    if (!$this->isValid())
    {
      throw new LogicException('The form is not valid.');
    }

    $sendMail = $this->getOption('sendMail', true);

    NewsletterSubscriberTable::getInstance()
      ->subscribe($this->values['mail_address'], $sendMail);
  }

  public function doUnsubscribe()
  {
    if (!$this->isValid())
    {
      throw new LogicException('The form is not valid.');
    }

    $sendMail = $this->getOption('sendMail', true);

    NewsletterSubscriberTable::getInstance()
      ->unsubscribe($this->values['mail_address'], $sendMail);
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
