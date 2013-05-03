<?php
///	***	Class :: ACL	***	***	***	***	***	***	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																	*
	 * @copyright 2013
	 * 		by
	 * @author Vitaliy Tsutsman
	 * 
	 * @date 2013-04-14 17:18:21 :: 2013-04-14 18:30:45
	 * 
	 * @address Poland/Krakow/Budryka/5/414
	 * 
	 * @description
	 *	Mannagment of users this service
	 *																	*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class Acl_Acl
	extends
		Zend_Acl
{
	///	***	Constants	***	///
	//- Users -//
	const ROLE_GUEST 			= 'guest';
	//const ROLE_STUDENT 		= 'student';
	const ROLE_PROFESSOR 		= 'professor';
	const ROLE_ADMINISTRATOR 	= 'admin';
	
	
	//- Resources -//
	const RESOURCE_ERROR			= 'error';
	
	const RESOURCE_AUTHORIZATION	= 'authorization';
	const RESOURCE_LOCALE			= 'locale';	
	
	const RESOURCE_GUEST 			= 'index';
	//const RESOURCE_STUDENT 		= 'student';
	const RESOURCE_PROFESSOR 		= 'professor';
	const RESOURCE_ADMINISTRATOR 	= 'administrator';
	
	
	//- Permited -//
	const PERM_VIEW 	= 'view';
	const PERM_EDIT 	= 'edit';
	const PERM_ADD 		= 'add';
	const PERM_DELETE 	= 'delete';
	
	///	*** Properties	***	///
	
	
	///	***	Methods		***	///
	public function __construct()
	{
		//- Create roles -//
		//- Guest -//
		$this -> addRole( 
			new Zend_Acl_Role( self :: ROLE_GUEST )
		);
		//- Professor -//
		$this -> addRole( 
			new Zend_Acl_Role( self :: ROLE_PROFESSOR ), 
			self :: ROLE_GUEST
		);
		//- Administration -//
		$this -> addRole(
			new Zend_Acl_Role( self :: ROLE_ADMINISTRATOR ), 
			self :: ROLE_PROFESSOR
		);
		
		
		//- Add resource -//
		//- Guest -//
		$this -> add( new Zend_Acl_Resource( self :: RESOURCE_ERROR ) );		
		$this -> add( new Zend_Acl_Resource( self :: RESOURCE_LOCALE ) );
		$this -> add( new Zend_Acl_Resource( self :: RESOURCE_AUTHORIZATION ) );
		
		$this -> add( new Zend_Acl_Resource( self :: RESOURCE_GUEST ) );
		
		//- Professor -//
		$this -> add( new Zend_Acl_Resource( self :: RESOURCE_PROFESSOR ) );
		
		//- Administrator -//
		$this -> add( new Zend_Acl_Resource( self :: RESOURCE_ADMINISTRATOR )	);


		//- Set permittion -//
		$this -> deny( null, null, null );
		
		//- Errors -//
		$this -> allow( 
			self :: ROLE_GUEST, 
			self :: RESOURCE_ERROR, 
			self :: PERM_VIEW
		);
		
		//- Localization -//
		$this -> allow( 
			self :: ROLE_GUEST, 
			self :: RESOURCE_LOCALE, 
			self :: PERM_VIEW
		);
		
		//- Authorization -//
		$this -> allow( 
			self :: ROLE_GUEST, 
			self :: RESOURCE_AUTHORIZATION, 
			self :: PERM_VIEW
		);
		
		//- Guest -//
		$this -> allow( 
			self :: ROLE_GUEST, 
			self :: RESOURCE_GUEST, 
			self :: PERM_VIEW
		);

		//- Professor -//
		$this -> allow( 
			self :: ROLE_PROFESSOR, 
			self :: RESOURCE_PROFESSOR, 
			self :: PERM_VIEW
		);
		
		//- Administrator -//
		$this -> allow( 
			self :: ROLE_ADMINISTRATOR, 
			self :: RESOURCE_ADMINISTRATOR, 
			self :: PERM_VIEW
		);
	}
	
	
	public function can( $Permission = 'show' )
	{
		//TODO: ?
		$request = Zend_Controller_Front :: getInstance() -> getRequest();
		
		$resource = $request -> getControllerName() . '/' . $request -> getActionName();
		
		if( !$this -> has( $resource ) ){ return true; }
		
		$storrage_data = Zend_Auth :: getInstance() -> getStorage() -> read();
		
		$role = array_key_exists( 'status', $storrage_data ) ? $storrage_data -> status : 'guest';
		
		return $this -> isAllowed( $role, $resource, $Permission );
	}
}