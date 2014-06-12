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

  // TODO: opNewsletterSubscriptionForm と重複している処理を共通化

  public function doSubscribeAll()
  {
    if (!$this->isValid())
    {
      throw new LogicException('The form is not valid.');
    }

    $conn = $this->getConnection();
    $conn->beginTransaction();
    try
    {
      foreach ($this->values['mail_address'] as $mailAddress)
      {
        $subscriber = NewsletterSubscriberTable::getInstance()
          ->findOneByMailAddressForUpdate($mailAddress);

        if (!$subscriber)
        {
          $subscriber = NewsletterSubscriberTable::getInstance()->create(array(
            'mail_address' => $mailAddress,
          ));
          $subscriber->save($conn);
          $subscriber->free(true);
        }
      }

      $conn->commit();
    }
    catch (Exception $ex)
    {
      $conn->rollback();
      throw $ex;
    }
  }

  public function doUnsubscribeAll()
  {
    if (!$this->isValid())
    {
      throw new LogicException('The form is not valid.');
    }

    $conn = $this->getConnection();
    $conn->beginTransaction();
    try
    {
      foreach ($this->values['mail_address'] as $mailAddress)
      {
        $subscriber = NewsletterSubscriberTable::getInstance()
          ->findOneByMailAddressForUpdate($mailAddress);

        if ($subscriber)
        {
          $subscriber->delete($conn);
          $subscriber->free(true);
        }
      }

      $conn->commit();
    }
    catch (Exception $ex)
    {
      $conn->rollback();
      throw $ex;
    }
  }

  public function getConnection()
  {
    return opDoctrineQuery::getMasterConnection();
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
