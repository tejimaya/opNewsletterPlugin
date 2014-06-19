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

  public function setAddLimitValidator()
  {
    $validator = new sfValidatorCallback(array(
      'callback' => array($this, 'validateSubscriberLimit'),
      'arguments' => array('remove' => false),
    ));

    $this->validatorSchema->setPostValidator($validator);
  }

  public function setRemoveLimitValidator()
  {
    $validator = new sfValidatorCallback(array(
      'callback' => array($this, 'validateSubscriberLimit'),
      'arguments' => array('remove' => true),
    ));

    $this->validatorSchema->setPostValidator($validator);
  }

  public function validateSubscriberLimit(sfValidatorCallback $validator, array $values, array $arguments)
  {
    $subscriberLimit = sfConfig::get('op_newsletter_subscriber_limit', 1000);
    $subscriberCount = NewsletterSubscriberTable::getInstance()->count();

    $addCount = count($values['mail_address']);
    if ($arguments['remove'])
    {
      $addCount *= -1;
    }

    if ($subscriberCount + $addCount > $subscriberLimit)
    {
      if (!$arguments['remove'])
      {
        $message = sprintf('ニュースレター配信登録数%d件を超えてしまうため登録できません。現在登録可能な件数は%d件です。',
          $subscriberLimit, $subscriberLimit - $subscriberCount);
      }
      else
      {
        $message = sprintf('入力されたメールアドレスが%d件を超えているため削除できません。メールアドレスの入力は%d件までとして下さい。',
          $subscriberLimit, $subscriberLimit);
      }

      throw new sfValidatorErrorSchema($validator, array(
        'mail_address' => new sfValidatorError($validator, $message),
      ));
    }

    return $values;
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
