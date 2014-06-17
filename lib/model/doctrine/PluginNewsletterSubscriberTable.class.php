<?php

/**
 * PluginNewsletterSubscriberTable
 *
 * @package    opNewsletterPlugin
 * @subpackage model
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
class PluginNewsletterSubscriberTable extends Doctrine_Table
{
  /**
   * Returns an instance of this class.
   *
   * @return object PluginNewsletterSubscriberTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('PluginNewsletterSubscriber');
  }

  public function findOneByMailAddressForUpdate($mailAddress)
  {
    return $this->createQuery()
      ->forUpdate()
      ->andWhere('mail_address = ?', $mailAddress)
      ->fetchOne();
  }

  public function subscribe($mailAddress, $sendMail = false)
  {
    $this->subscribeAll(array($mailAddress), $sendMail);
  }

  public function subscribeAll(array $mailAddresses, $sendMail = false)
  {
    $conn = $this->getConnection();
    $conn->beginTransaction();
    try
    {
      foreach ($mailAddresses as $mailAddress)
      {
        $subscriber = $this->findOneByMailAddressForUpdate($mailAddress);

        if (!$subscriber)
        {
          $subscriber = $this->create(array(
            'mail_address' => $mailAddress,
          ));
          $subscriber->save($conn);

          if ($sendMail)
          {
            $this->sendMail('newsletterSubscribe', $mailAddress);
          }
        }

        $subscriber->free(true);
      }

      $conn->commit();
    }
    catch (Exception $ex)
    {
      $conn->rollback();
      throw $ex;
    }
  }

  public function unsubscribe($mailAddress, $sendMail = false)
  {
    $this->unsubscribeAll(array($mailAddress), $sendMail);
  }

  public function unsubscribeAll(array $mailAddresses, $sendMail = false)
  {
    $conn = $this->getConnection();
    $conn->beginTransaction();
    try
    {
      foreach ($mailAddresses as $mailAddress)
      {
        $subscriber = $this->findOneByMailAddressForUpdate($mailAddress);

        if ($subscriber)
        {
          $subscriber->delete($conn);
          $subscriber->free(true);

          if ($sendMail)
          {
            $this->sendMail('newsletterUnsubscribe', $mailAddress);
          }
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

  protected function sendMail($templateName, $toMailAddress, $params = array())
  {
    static $fromMailAddress = null;
    if (null === $fromMailAddress)
    {
      $fromMailAddress = opConfig::get('admin_mail_address');
    }

    opMailSend::sendTemplateMail($templateName, $toMailAddress, $fromMailAddress, $params + array(
      'target' => 'pc',
    ));
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
