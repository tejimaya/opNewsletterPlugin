<?php

/**
 * @package     opNewsletterPlugin
 * @subpackage  migration
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class opNewsletterPlugin_Revision1 extends opMigration
{
  public function migrate($direction)
  {
    $this->table($direction, 'newsletter_subscriber', array(
      'id' => array(
        'type' => 'integer',
        'primary' => true,
        'autoincrement' => true,
        'length' => 4,
      ),
      'mail_address' => array(
        'type' => 'string',
        'notnull' => true,
        'length' => 255,
      ),
      'is_active' => array(
        'type' => 'boolean',
        'notnull' => true,
        'default' => true,
        'length' => 25,
      ),
      'created_at' => array(
        'type' => 'timestamp',
        'notnull' => true,
        'length' => 25,
      ),
      'updated_at' => array(
        'type' => 'timestamp',
        'notnull' => true,
        'length' => 25,
      ),
    ), array(
      'indexes' => array(
        'mail_address' => array(
          'fields' => array('mail_address'),
          'type' => 'unique',
        ),
        'is_active' => array(
          'fields' => array('is_active'),
        ),
      ),
      'primary' => array('id'),
      'charset' => 'utf8',
    ));
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
