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
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2