<?php
///	***	Class :: ACL	***	***	***	***	***	***	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																	*
	 * @copyright 2013
	 * 		by
	 * @author Vitaliy Tsutsman
	 * 
	 * @date 2013-04-14 17:34:45 :: 2013-04-.. ..:..:..
	 * 
	 * @address Poland/Krakow/Budryka/5/414
	 * 
	 * @description
	 *	Mannagment of users this service
	 *																	*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class CheckAccess
	extends
		Zend_Controller_Plugin_Abstract
{
	///	***	Properties	***	///
	private $role;					//- Current role -//
	
	//- System -//
	private $_acl;					//- Object from ACL -//
	
	
	///	***	Methods		***	//
	public function __construct(
		Zend_Acl $Acl
	)
	{
		//- Init -//
		$this -> _acl = $Acl;
		
		//- Default -//
		$this -> role = Acl_Acl :: ROLE_GUEST;
	}
	
	public function preDispatch(
		Zend_Controller_Request_Abstract $Request
	)
	{
		//- Get role -//
		if( Zend_Auth :: getInstance() -> hasIdentity() )
		{
			$session = new Zend_Session_Namespace( 'system.user' );
			$this -> role = $session -> user -> role;
		}
		
		//- Get resource -//
		$resources = array( 
			'error', 'index', 'locale', 'authorization', 
			'professor', 'admin' 
		);
		
		$resource = $Request -> getControllerName();
		
		//- Test params -//
		if( 
			!( 
				Zend_Validate :: is( $resource, 'InArray', array( $resources ) ) 
				&& $this -> _acl -> isAllowed( $this -> role, $resource, 'view' ) 
			)
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
