<?php
///	***	Acl for this service	***	***	***	***	***	***	***	***	***	***	***	///

	/**
	 * 
	 */

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class Acl_Acl extends Zend_Acl
{
	
	public function __construct()
	{
		//- Create roles -//
		$this -> addRole( new Zend_Acl_Role( 'Guest' ) );
		$this -> addRole( new Zend_Acl_Role( 'Client' ), 'Guest' );
		$this -> addRole( new Zend_Acl_Role( 'Realtor' ), 'Client' );
		$this -> addRole( new Zend_Acl_Role( 'Admin' ), 'Realtor' );
		
		//- Add resource -//
		//- Guest -//
		$this -> add( new Zend_Acl_Resource( 'error' ) );
		$this -> add( new Zend_Acl_Resource( 'index' ) );
		$this -> add( new Zend_Acl_Resource( 'locale' ) );
		$this -> add( new Zend_Acl_Resource( 'auth' ) );		
				
		//- Client -//
		$this -> add( new Zend_Acl_Resource( 'client' ) );
		//$this -> add( new Zend_Acl_Resource( 'client_allow' ) );
		//$this -> add( new Zend_Acl_Resource( '/:locale/register' ), 'client_allow' );
		
		//- Realtor -//
		$this -> add( new Zend_Acl_Resource( 'realtor' ), 'client' );
				
		//- Admin -//
		$this -> add( new Zend_Acl_Resource( 'admin' ), 'realtor' );
		//$this -> add( new Zend_Acl_Resource( '/request' ), 'client_allow' );

		//- Set permission -//
		$this -> deny( null, null, null );
		$this -> allow( 'Guest', 'error', 'view' );
		$this -> allow( 'Guest', 'index', 'view' );
		$this -> allow( 'Guest', 'locale', 'view' );
		$this -> allow( 'Guest', 'auth', 'view' );
		
		$this -> allow( 'Client', 'client', 'view' );
		/*$this -> allow( 'guest', 'guest_allow', 'show' );
		$this -> allow( 'client', 'client_allow', 'show' );
		$this -> allow( 'realtor', 'realtor_allow', 'show' );
		$this -> allow( 'admin', 'admin_allow', 'show' );*/
	}
	
	
	public function can( $Permission = 'show' )
	{
		$request = Zend_Controller_Front :: getInstance() -> getRequest();
		
		$resource = $request -> getControllerName() . '/' . $request -> getActionName();
		
		if( !$this -> has( $resource ) ){ return true; }
		
		$storrage_data = Zend_Auth :: getInstance() -> getStorage() -> read();
		
		$role = array_key_exists( 'status', $storrage_data ) ? $storrage_data -> status : 'guest';
		
		return $this -> isAllowed( $role, $resource, $Permission );
	}
}