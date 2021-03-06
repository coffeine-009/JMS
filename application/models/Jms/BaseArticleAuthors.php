<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Jms_ArticleAuthors', 'doctrine');

/**
 * Jms_BaseArticleAuthors
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_user
 * @property integer $id_article
 * @property Jms_User $User
 * @property Jms_Article $Article
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Jms_BaseArticleAuthors extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('article_authors');
        $this->hasColumn('id_user', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('id_article', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Jms_User as User', array(
             'local' => 'id_user',
             'foreign' => 'id'));

        $this->hasOne('Jms_Article as Article', array(
             'local' => 'id_article',
             'foreign' => 'id'));
    }
}