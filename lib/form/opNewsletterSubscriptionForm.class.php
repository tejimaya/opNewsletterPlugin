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

    $conn = $this->getConnection();
    $conn->beginTransaction();
    try
    {
      $mailAddress = $this->values['mail_address'];

      $subscriber = NewsletterSubscriberTable::getInstance()
        ->findOneByMailAddressForUpdate($mailAddress);

      if (!$subscriber)
      {
        $subscriber = NewsletterSubscriberTable::getInstance()->create(array(
          'mail_address' => $mailAddress,
        ));
        $subscriber->save($conn);

        $this->sendMail('newsletterSubscribe', $mailAddress);
      }

      $conn->commit();
    }
    catch (Exception $ex)
    {
      $conn->rollback();
      throw $ex;
    }
  }

  protected function sendMail($templateName, $toMailAddress, $params = array())
  {
    $fromMailAddress = opConfig::get('admin_mail_address');

    opMailSend::sendTemplateMail($templateName, $toMailAddress, $fromMailAddress, $params + array(
      'target' => 'pc',
    ));
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
