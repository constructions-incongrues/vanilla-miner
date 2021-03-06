<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Addlink extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('link', array(
             'id' =>
             array(
              'type' => 'integer',
              'autoincrement' => true,
              'primary' => true,
              'length' => 8,
             ),
             'url' =>
             array(
              'type' => 'string',
              'notnull' => true,
              'length' => NULL,
             ),
             'domain_parent' =>
             array(
              'type' => 'string',
              'notnull' => true,
              'length' => NULL,
             ),
             'domain_fqdn' =>
             array(
              'type' => 'string',
              'notnull' => true,
              'length' => NULL,
             ),
             'mime_type' =>
             array(
              'type' => 'string',
              'length' => NULL,
             ),
             'contributed_at' =>
             array(
              'type' => 'string',
              'length' => NULL,
             ),
             'contributor_id' =>
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'contributor_name' =>
             array(
              'type' => 'string',
              'length' => NULL,
             ),
             'comment_id' =>
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'discussion_id' =>
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'discussion_name' =>
             array(
              'type' => 'string',
              'length' => NULL,
             ),
             'availability' =>
             array(
              'type' => 'string',
              'default' => 'unknown',
              'length' => NULL,
             ),
             'expanded_at' =>
             array(
              'type' => 'timestamp',
              'length' => 25,
             ),
             'created_at' =>
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             'updated_at' =>
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             ), array(
             'indexes' =>
             array(
              'idx_url' =>
              array(
              'fields' =>
              array(
               'url' =>
               array(
               'length' => 512,
               ),
              ),
              'type' => 'unique',
              ),
              'idx_expanded_at' =>
              array(
              'fields' =>
              array(
               0 => 'expanded_at',
              ),
              ),
              'idx_availability' =>
              array(
              'fields' =>
              array(
               'availability' =>
               array(
               'length' => 11,
               ),
              ),
              ),
             ),
             'primary' =>
             array(
              0 => 'id',
             ),
             ));
    }

    public function down()
    {
        $this->dropTable('link');
    }
}