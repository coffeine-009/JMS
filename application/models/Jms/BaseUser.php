<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Jms_User', 'doctrine');

/**
 * Jms_BaseUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $id_role
 * @property integer $id_status
 * @property string $first_name
 * @property string $second_name
 * @property string $father_name
 * @property integer $gender
 * @property string $country
 * @property string $language
 * @property timestamp $creation
 * @property Jms_Role $Role
 * @property Jms_UserStatus $UserStatus
 * @property Doctrine_Collection $Article
 * @property Doctrine_Collection $ArticleAuthors
 * @property Doctrine_Collection $Email
 * @property Doctrine_Collection $Recension
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Jms_BaseUser extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('id_role', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('id_status', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('first_name', 'string', 16, array(
             'type' => 'string',
             'length' => 16,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('second_name', 'string', 32, array(
             'type' => 'string',
             'length' => 32,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('father_name', 'string', 32, array(
             'type' => 'string',
             'length' => 32,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('gender', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('country', 'string', 2, array(
             'type' => 'string',
             'length' => 2,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('language', 'string', 2, array(
             'type' => 'string',
             'length' => 2,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('creation', 'timestamp', null, array(
             'type' => 'timestamp',
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
        $this->hasOne('Jms_Role as Role', array(
             'local' => 'id_role',
             'foreign' => 'id'));

        $this->hasOne('Jms_UserStatus as UserStatus', array(
             'local' => 'id_status',
             'foreign' => 'id'));

        $this->hasMany('Jms_Article as Article', array(
             'local' => 'id',
             'foreign' => 'id_user'));

        $this->hasMany('Jms_ArticleAuthors as ArticleAuthors', array(
             'local' => 'id',
             'foreign' => 'id_user'));

        $this->hasMany('Jms_Email as Email', array(
             'local' => 'id',
             'foreign' => 'id_user'));

        $this->hasMany('Jms_Recension as Recension', array(
             'local' => 'id',
             'foreign' => 'id_user'));
    }
}