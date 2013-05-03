<?php

class Auth_Adapter
	implements
		Zend_Auth_Adapter_Interface
{
	///	***	Constants	***	///
	const AUTH = 
	"SELECT 
		user.id, 
		role.title AS role, 
		email.contact 
	FROM 
		(user 
			LEFT JOIN 
		email 
			ON( user.id = email.id_user )
		)
			LEFT JOIN 
		role 
			ON( role.id = user.id_role ) 
	WHERE 
		email.contact = '%s' 
		AND 
		user.password = MD5( '%s' ) 
	LIMIT 
		1
	";
		
	///	***	Properties	***	///
	protected $userData;
	
	///	***	Methods		***	///
	public function __construct( /*string*/$UserName, /*string*/$Password )
	{
		//- Set properties -//
		$this -> username = $UserName;
		$this -> password = $Password;
	}
	
	public function authenticate()
	{		
		$response = Doctrine_Query :: create()
			/*-> from( "Jms_Email e" )
			-> leftJoin( "e.User u" )
			-> leftJoin( "u.Role" )*/			
			//- TMP -//
			-> from( "Jms_User u" )
			-> addFrom( "u.Email e" )
			-> addFrom( "u.Role r" )
			
			-> where( 
				"address = '{$this -> username}' 
				AND 
				password = MD5( '{$this -> password}' )" 
			)
		
			-> limit( 1 );
		
		$response -> execute();
			
		$userData = $response->fetchArray();

		//- Test response from DB -//
		if( count( $userData ) === 1 )
		{
			//- Get data -//
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
	
	public function getUserData()// : array
	{
		return $this -> userData;
	}
}