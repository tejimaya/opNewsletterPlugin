<?php

/**
 * @package     opNewsletterPlugin
 * @subpackage  action
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class newsletterComponents extends sfComponents
{
  public function executeSubscriberList(opWebRequest $request)
  {
    $this->subscribers = NewsletterSubscriberTable::getInstance()->findAll();
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
