<?php

class Auth_Adapter implements Zend_Auth_Adapter_Interface
{
	///	***	Properties	***	///
	protected $link;
	protected $userData;
	
	///	***	Methods		***	///
	public function __construct( /*string*/$UserName, /*string*/$Password )
	{
		$this -> username = $UserName;
		$this -> password = $Password;
		
		$param = new Zend_Config_Ini( 'configs/application.ini', 'production' );
	
		$params = array(
			'host'		=> $param -> resources -> db -> params -> host, 
			'username'	=> $param -> resources -> db -> params -> username, 
			'password'	=> $param -> resources -> db -> params -> password, 
			'dbname'	=> $param -> resources -> db -> params -> dbname
		);
		
		$this -> link = Zend_Db :: factory( $param  -> resources -> db -> adapter, $params );
	}
	
	public function authenticate()
	{
		$sql = "SELECT 
			user.id, 
			role.title AS role, 
			email.mail 
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
			email.mail = '{$this -> username}' 
			AND 
			user.password = MD5( '{$this -> password}' ) 
		LIMIT 
			1
		";
		
		$res = $this -> link -> query( $sql ) -> fetch();
		
		if( $res )
		{
			$this -> userData = $res;
			
			return new Zend_Auth_Result(
				Zend_Auth_Result :: SUCCESS, $res[ 'id' ], array()
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