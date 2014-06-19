<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$t = new lime_test(6);

$conn = Doctrine_Manager::connection();
sfForm::disableCSRFProtection();

//------------------------------------------------------------------------------
$t->diag('opNewsletterSubscriberManageForm::validateMailAddress()');

$form = new opNewsletterSubscriberManageForm();
$form->bind(array(
  'mail_address' => <<<'EOT'
sns+01@example.com
sns+02@example.com
EOT
));

$t->ok($form->isValid());
$t->is($form->getValues(), array(
  'mail_address' => array(
    'sns+01@example.com',
    'sns+02@example.com',
  ),
));

//------------------------------------------------------------------------------
$t->diag('opNewsletterSubscriberManageForm::setAddLimitValidator()');

$conn->beginTransaction();

// 既に登録されている購読者
$subscribers = new Doctrine_Collection('NewsletterSubscriber');
$subscribers[] = NewsletterSubscriberTable::getInstance()->create(array(
  'mail_address' => 'sns+01@example.com',
));
$subscribers[] = NewsletterSubscriberTable::getInstance()->create(array(
  'mail_address' => 'sns+02@example.com',
));
$subscribers->save($conn);

// 登録件数上限を 4 件にセット
sfConfig::set('op_newsletter_subscriber_limit', 4);

$form = new opNewsletterSubscriberManageForm();
$form->setAddLimitValidator();

// 3 件以上追加すると上限に収まらない
$form->bind(array(
  'mail_address' => <<<'EOT'
sns+03@example.com
sns+04@example.com
EOT
));
$t->ok($form->isValid());

$form->bind(array(
  'mail_address' => <<<'EOT'
sns+03@example.com
sns+04@example.com
sns+05@example.com
EOT
));
$t->ok(!$form->isValid());

$conn->rollback();

//------------------------------------------------------------------------------
$t->diag('opNewsletterSubscriberManageForm::setRemoveLimitValidator()');

$conn->beginTransaction();

// 既に登録されている購読者
$subscribers = new Doctrine_Collection('NewsletterSubscriber');
$subscribers[] = NewsletterSubscriberTable::getInstance()->create(array(
  'mail_address' => 'sns+01@example.com',
));
$subscribers[] = NewsletterSubscriberTable::getInstance()->create(array(
  'mail_address' => 'sns+02@example.com',
));
$subscribers[] = NewsletterSubscriberTable::getInstance()->create(array(
  'mail_address' => 'sns+03@example.com',
));
$subscribers[] = NewsletterSubscriberTable::getInstance()->create(array(
  'mail_address' => 'sns+04@example.com',
));
$subscribers->save($conn);

// 登録件数上限を 2 件にセット
sfConfig::set('op_newsletter_subscriber_limit', 2);

$form = new opNewsletterSubscriberManageForm();
$form->setRemoveLimitValidator();

// 2 件以上削除しないと上限に収まらない
$form->bind(array(
  'mail_address' => <<<'EOT'
sns+04@example.com
EOT
));
$t->ok(!$form->isValid());

$form->bind(array(
  'mail_address' => <<<'EOT'
sns+03@example.com
sns+04@example.com
EOT
));
$t->ok($form->isValid());

$conn->rollback();

// vim: et fenc=utf-8 sts=2 sw=2 ts=2
