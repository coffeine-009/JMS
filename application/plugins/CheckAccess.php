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
			$this -> role = $session -> user[ 'role' ][ 'title' ];
		}
		
		//- Get resource -//
		$resources = array(
			Acl_Acl :: RESOURCE_ERROR, 
			Acl_Acl :: RESOURCE_LOCALE, 
			Acl_Acl :: RESOURCE_AUTHORIZATION, 
			Acl_Acl :: RESOURCE_GUEST, 
			Acl_Acl :: RESOURCE_AUTHOR, 
			Acl_Acl :: RESOURCE_ADMINISTRATOR 
		);
		
		$controller = $Request -> getControllerName();
		$action = $Request -> getActionName();
		
		//- Guest -//
		$resource = Acl_Acl :: RESOURCE_GUEST;
		
		switch( $controller )
		{
			//- Author -//
			case 'author':
			{
				$resource = Acl_Acl :: RESOURCE_AUTHOR;
			}break;
			
			
			//- Admin -//
			case 'article':
			{
				$resource = Acl_Acl :: RESOURCE_AUTHOR;
				
				switch( $action )
				{
					case 'addtojournal':
					case 'deletefromjournal':
					{
						$resource = Acl_Acl :: RESOURCE_ADMINISTRATOR;
					}break;
					
					case 'view':
					{
						$resource = Acl_Acl :: RESOURCE_GUEST;
					}break;
				}
			}break;
			
			case 'journal':
			{
				switch( $action )
				{
					case 'add':
					case 'edit':
					case 'delete':
					{
						$resource = Acl_Acl :: RESOURCE_ADMINISTRATOR;
					}break;
				}
			}break;
			
			case 'journalnumber':
			{
				switch( $action )
				{
					case 'add':
					case 'edit':
					case 'delete':
					{
						$resource = Acl_Acl :: RESOURCE_ADMINISTRATOR;
					}break;
				}
			}break;
			
			
				
			case 'admin':
			{				
				$resource = Acl_Acl :: RESOURCE_ADMINISTRATOR;
			}break;
		}
		
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
