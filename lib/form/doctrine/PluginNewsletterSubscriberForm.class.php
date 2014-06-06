<?php

/**
 * PluginNewsletterSubscriber form.
 *
 * @package    opNewsletterPlugin
 * @subpackage form
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
abstract class PluginNewsletterSubscriberForm extends BaseNewsletterSubscriberForm
{
  protected function setupInheritance()
  {
    $this->setValidator('mail_address', new sfValidatorEmail(array(
      'max_length' => 255,
    )));
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
