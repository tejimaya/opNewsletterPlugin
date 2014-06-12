<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$t = new lime_test(2);

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

// vim: et fenc=utf-8 sts=2 sw=2 ts=2
