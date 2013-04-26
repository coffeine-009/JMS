<?php

class CheckAccess extends Zend_Controller_Plugin_Abstract
{
	///	***	Properties	***	///
	private $_acl;
	
	///	***	Methods		***	//
	public function __construct( Zend_Acl $Acl )
	{
		//- Init -//
		$this -> _acl = $Acl;
	}
	
	public function preDispatch( Zend_Controller_Request_Abstract $Request )
	{
		//- Get role -//
		$role = 'Guest';
		if( Zend_Auth :: getInstance() -> hasIdentity() )
		{
			$session = new Zend_Session_Namespace( 'system.user' );
			$role = $session -> user -> role;
		}
		
		//- Get resource -//
		$resources = array( 'error', 'index', 'locale', 'auth', 'client', 'realtor', 'admin' );
		
		$resource = $Request -> getControllerName();
		
		//- Test params -//
		if( !Zend_Validate :: is( $resource, 'InArray', array( $resources ) ) 
			&& !$this -> _acl -> isAllowed( $role, $resource, 'view' )
		)
		{		
			//- Access deny -//
			$Request 
				-> setModuleName( 'default' )
				-> setControllerName( 'error' )
				-> setActionName( 'accessdeny' );
		}
	}
	
	public function generateAccessError( $Message = 'Access denny' )
	{
		$request = $this -> getRequest();
		$request -> setControllerName( 'error' );
		$request -> setActionName( 'error' );
		$request -> setParam( 'message', $Message );
	}
}