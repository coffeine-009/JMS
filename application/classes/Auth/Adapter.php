<?php
///	***	Class :: Adapter :: Authorization	***	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * 	@author Vitaliy Tsutsman
	 * 
	 * @date 2013-05-03 23:13:18 :: 2013-05-03 23:15:00
	 * 
	 * @address Ukraine/Petranka/Gryshevskii/234
	 * 
	 * @description
	 * 	Auth adapter used ORM Doctrine.
	 * 																*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class Auth_Adapter
	implements
		Zend_Auth_Adapter_Interface
{		
	///	***	Properties	***	///
	protected $userData;
	
	///	***	Methods		***	///
	public function __construct( 
		/*string*/$UserName, 
		/*string*/$Password 
	)
	{
		//- Set properties -//
		$this -> username = $UserName;
		$this -> password = $Password;
	}
	
	//- Authenticate -//
	public function authenticate()// : bool
	{
		//- Query to DB -//
		$response = Doctrine_Query :: create()
			//- First query -//
			/*-> from( "Jms_Email e" )
			-> leftJoin( "e.User u" )
			-> leftJoin( "u.Role" )*/			
			//- Better query -//
			-> from( "Jms_User u" )
			-> addFrom( "u.Email e" )
			-> addFrom( "u.Role r" )
			//- Where -//
			-> where( 
				"address = '{$this -> username}' 
				AND 
				password = MD5( '{$this -> password}' )" 
			)		
			-> limit( 1 );
		
		$response -> execute();
		
		//- Get response -//
		$userData = $response->fetchArray();

		//- Test response from DB -//
		if( count( $userData ) === 1 )
		{
			//- Set structuration data -//
			$this -> userData = array(
				'id'		=> $userData[ 0 ][ 'id' ], 
				'username'	=> $userData[ 0 ][ 'username' ], 
				'first_name'=> $userData[ 0 ][ 'first_name' ], 
				'last_name'	=> $userData[ 0 ][ 'second_name' ], 
				'gender'	=> $userData[ 0 ][ 'gender' ], 
				'country'	=> $userData[ 0 ][ 'country' ], 
				'language'	=> $userData[ 0 ][ 'language' ], 
				//TODO: tools
				'role'	=> array(
					'id'	=> $userData[ 0 ][ 'Role' ][ 'id' ], 
					'title'	=> $userData[ 0 ][ 'Role' ][ 'title' ]
				), 
				'email'	=> array(
					'id'		=> $userData[ 0 ][ 'Email' ][ 0 ][ 'id' ], 
					'address'	=> $userData[ 0 ][ 'Email' ][ 0 ][ 'address' ]
				)
			);
						
			return new Zend_Auth_Result(
				Zend_Auth_Result :: SUCCESS, $this -> userData[ 'id' ], array()
			);
		}else
			{
				return new Zend_Auth_Result(
					Zend_Auth_Result :: FAILURE, 
					null, 
					array( 'Authentification is unsuccessfull' )
				);
			}
	}
	
	//- Get params about authenticate user -//
	public function getUserData()// : array.asotiation
	{
		return $this -> userData;
	}
}