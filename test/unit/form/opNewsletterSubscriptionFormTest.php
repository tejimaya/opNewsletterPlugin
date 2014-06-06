<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$t = new lime_test(9);

$conn = Doctrine_Manager::connection();

class opNewsletterSubscriptionForm_Mock extends opNewsletterSubscriptionForm
{
  public $lastMail, $lastMailTo;

  protected function sendMail($templateName, $toMailAddress, $params = array())
  {
    $this->lastMail = $templateName;
    $this->lastMailTo = $toMailAddress;
  }
}

//------------------------------------------------------------------------------
$t->diag('opNewsletterSubscriptionForm::doSubscribe()');

$conn->beginTransaction();

$form = new opNewsletterSubscriptionForm_Mock();
$form->bind(array(
  'mail_address' => 'sns@example.com',
));

$t->ok($form->isValid());

$form->doSubscribe();

$t->is($form->lastMail, 'newsletterSubscribe');
$t->is($form->lastMailTo, 'sns@example.com');

$subscriber = NewsletterSubscriberTable::getInstance()
  ->findOneByMailAddress('sns@example.com');

$t->ok($subscriber);
$t->ok($subscriber->is_active);

$conn->rollback();

//------------------------------------------------------------------------------
$t->diag('opNewsletterSubscriptionForm::doUnsubscribe()');

$conn->beginTransaction();

// 既に登録されている購読者
$subscriber = NewsletterSubscriberTable::getInstance()->create(array(
  'mail_address' => 'sns@example.com',
));
$subscriber->save($conn);

$form = new opNewsletterSubscriptionForm_Mock();
$form->bind(array(
  'mail_address' => 'sns@example.com',
));

$t->ok($form->isValid());

$form->doUnsubscribe();

$t->is($form->lastMail, 'newsletterUnsubscribe');
$t->is($form->lastMailTo, 'sns@example.com');

$subscriber = NewsletterSubscriberTable::getInstance()
  ->findOneByMailAddress('sns@example.com');

$t->ok(!$subscriber);

$conn->rollback();

// vim: et fenc=utf-8 sts=2 sw=2 ts=2
