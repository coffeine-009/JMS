<?php
///	***	Class :: Controller :: Authorization	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * 	@author Vitaliy Tsutsman
	 * 
	 * @date 2013-04-04 17:48:46 :: 2013-..-.. ..:..:..
	 * 
	 * @address Paland/Krakow/Budryka/5/414
	 * 																*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Include other files	***	***	***	***	***	***	***	***	***	***	***	***	///
require_once APPLICATION_PATH . '/controllers/BaseController.php';

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class AuthorizationController
	extends
		BaseController
		//Zend_Controller_Action
{

    public function init()
    {
    	parent :: init();
    	
    	//- Init others view formats -//
		$this -> _helper -> getHelper( 'contextSwitch' )
			-> addActionContext( 'test', 'json' )
			-> initContext();
    }

    public function indexAction()
    {
        // action body
    }

    //- Registration action -//
    public function registrationAction()
    {
    	//- Include css -//
    	$this -> view -> headLink() -> appendStylesheet(
    		'/client/application/views/styles/registration.css'
    	);
    	
    	//- Include JS -//
    	$this -> view -> headScript() -> appendFile(
    		'/client/application/views/scripts/registration.js'
    	);
    	$this -> view -> headScript() -> appendFile(
    		'/client/library/Coffeine/Connect/Ajax/Connect.js'
    	);
    	
    	//- Get list of roles -//
    	$roles = Doctrine_Query :: create()
    		-> from( "Jms_Role" )
    		-> where( "title != 'administrator'" )
    		-> orderBy( "id" );
    		
    	$roles = $roles -> fetchArray();
    	
    	//- Init view -//
        $this -> view -> Title = 'Registration';
        $this -> view -> pathOfSite = 'Main => Registration';
        
        $this -> view -> roles = $roles;
        
    	
    	//- Array of arrays -//
    	$this -> errors = array(
    		'username'	=> array(), 
    		'password'	=> array(), 
    		'r_passwd'	=> array(), 
    		'gender'	=> array(), 
    		//- Names -//
    		'first_name'	=> array(), 
    		'last_name'		=> array(), 
    		'middle_name'	=> array(),
    		//- Contacts -//
    		'email'				=> array(), 
    		'phone'				=> array(), 
    		'skype'				=> array(), 
    	 	'mailing_address'	=> array(), 
    		'country'			=> array(), 
    		'language'			=> array(), 
    		'role'				=> array(), 
    		//- Params -//
    		'params'	=> array()
    	);
    	
    	//- Get data from form -//
    	if( $this -> getRequest() -> isPost() )
    	{
    		//- Get input data -//
    		$data = $this -> getRequest() -> getParams();
    		    		
    		//- Validators -//
			$vUserName 		= new Zend_Validate_Alnum();
			$vPassword		= new Zend_Validate_StringLength( array( 'min' => '6', 'max' => '80' ) );
			$vFirstName 	= new Zend_Validate_Alpha();
			$vSecondName 	= new Zend_Validate_Alpha();
			$vFatherName 	= new Zend_Validate_Alpha();
			$vGender		= new Zend_Validate_Regex( '/^[01]$/i' );
			$vEmail			= new Zend_Validate_EmailAddress();
			$vPhone			= new Zend_Validate_Regex( '/^\+[[:digit:]]{7,15}$/i' );
			$vSkype			= new Zend_Validate_Regex( '/^[^\<\>\&]{3,80}$/i' );
			$vAdress		= new Zend_Validate_Regex( '/^[^\<\>\&]+/i' );
			$vCountry		= new Zend_Validate_Regex( '/^[[:alpha:]]{2}$/i' );
			$vLanguage		= new Zend_Validate_Regex( '/^[[:alpha:]]{2}$/i' );
	
			//- Validation -//
			if( 
				$vUserName -> isValid( $data[ 'username' ] ) 
				&& $vPassword -> isValid( $data[ 'password' ] ) 
				&& $data[ 'password' ] === $data[ 'repeat_password' ] 
				&& $vFirstName -> isValid( $data[ 'first_name' ] ) 
				&& $vSecondName -> isValid( $data[ 'second_name' ] ) 
				&& $vFatherName -> isValid( $data[ 'father_name' ] ) 
				&& $vGender -> isValid( $data[ 'gender' ] ) 
				&& $vEmail -> isValid( $data[ 'email' ] ) 
				&& ( empty( $data[ 'phone' ] ) || $vPhone -> isValid( $data[ 'phone' ] ) ) 
				&& ( empty( $data[ 'skype' ] ) || $vSkype -> isValid( $data[ 'skype' ] ) ) 
				&& $vAdress -> isValid( $data[ 'address' ] ) 
				&& $vCountry -> isValid( $data[ 'country' ] ) 
				&& $vLanguage -> isValid( $data[ 'language' ] ) 
			)
			{
				//- Registration of new user -//
				$user = new Jms_User();
					//- Set role for new user -//
					foreach( $roles as $role )
						if( (int)$role[ 'id' ] === (int)$data[ 'role' ] ){
		    				$user -> id_role = (int)$data[ 'role' ];
		    				break;
						}					
		    		$user -> id_status = 2; //- Set status no active -//		    		
		    		//- Access -//
		    		$user -> username = $data[ 'username' ];
		    		$user -> password = md5( $data[ 'password' ] );
		    		//- Names -//
		    		$user -> first_name = $data[ 'first_name' ];
		    		$user -> second_name = $data[ 'second_name' ];
		    		$user -> father_name = $data[ 'father_name' ];
					//- Other -//
		    		$user -> gender = (int)$data[ 'gender' ];
		    		$user -> country = $data[ 'country' ];
		    		$user -> language = $data[ 'language' ];
		    	
		    	$user -> save();
		    	
		    	//- Add contacts -//
		    	//- Email -//
		    	$email = new Jms_Email();
		    		$email -> id_user = (int)$user -> id;
		    		$email -> address = $data[ 'email' ];
		    		
		    	$email -> save();

		    	//- Send letter for activate -//
		    	$user -> sendActivationLatter();
		    	//TODO: Review
				//- Add message -//
				$this -> _helper -> flashMessenger -> clearCurrentMessages();
				$this -> _helper -> flashMessenger 
					-> addMessage( 'Registration is success' )
					-> addMessage( 'Activate letter was sent. Read him, please' );

				//- Redirect to registration success -//
				$this -> _redirect( '/registration/success' );
			}else
			{
				//- Set input data -//
				$this -> view -> input = $data;
				
				//- Display errors, input data is invalid -//
				//- Username -//
				foreach( $vUserName -> getMessages() as $messageId => $message )
				{
					$this -> errors[ 'username' ][] = $messageId . ': ' . $message;
				}
				
				//-  Password -//
				foreach( $vPassword -> getMessages() as $messageId => $message )
				{
					$this -> errors[ 'password' ][] = $messageId . ': ' . $message;
				}
				
				//- Repeat passwrd -//
				if( $data[ 'password' ] !== $data[ 'repeat_password' ] )
				{
					$this -> errors[ 'r_passwd' ][] = 'Passwords not idential';
				}				
				
				//- First name -//
				foreach( $vFirstName -> getMessages() as $messageId => $message )
				{
					$this -> errors[ 'first_name' ][] = $messageId . ': ' . $message;
				}
				
				//- Last name -//
				foreach( $vSecondName -> getMessages() as $messageId => $message )
				{
					$this -> errors[ 'last_name' ][] = $messageId . ': ' . $message;
				}
				
				//- Middle name -//
				foreach( $vFatherName -> getMessages() as $messageId => $message )
				{
					$errors[ 'middle_name' ][] = $messageId . ': ' . $message;
				}
				
				//- Gender -//
				foreach( $vGender -> getMessages() as $messageId => $message )
				{
					$this -> errors[ 'gender' ][] = $messageId . ': ' . $message;
				}
				
				//- E-mail -//
				foreach( $vEmail -> getMessages() as $messageId => $message )
				{
					$this -> errors[ 'email' ][] = $messageId . ': ' . $message;
				}
				
				//- Phone -//
				foreach( $vPhone -> getMessages() as $messageId => $message )
				{
					$this -> errors[ 'phone' ][] = $messageId . ': ' . $message;
				}
				
				//- Skype -//
				foreach( $vSkype -> getMessages() as $messageId => $message )
				{
					$this -> errors[ 'skype' ][] = $messageId . ': ' . $message;
				}
				
				//- Country -//
				foreach( $vAdress -> getMessages() as $messageId => $message )
				{
					$this -> errors[ 'mailing_address' ][] = $messageId . ': ' . $message;
				}
				
				//- Country -//
				foreach( $vCountry -> getMessages() as $messageId => $message )
				{
					$this -> errors[ 'country' ][] = $messageId . ': ' . $message;
				}
				
				//- Language -//
				foreach( $vLanguage -> getMessages() as $messageId => $message )
				{
					$this -> errors[ 'language' ][] = $messageId . ': ' . $message;
				}
    		}
    	}
    }

    public function registrationsuccessAction()
    {
        //- Get message -//
        $this -> view -> messages = $this -> _helper -> flashMessenger -> getMessages();
        
        if( empty( $this -> view -> messages ) )
        {
        	//- Redirect to home -//
        	$this -> _redirect( '/' );
        }
    }

    public function loginAction()
    {
    	//- Init view -//
        $this -> view -> logotip = 'Authorization';
        $this -> view -> Title = 'Authorization';
        $this -> view -> pathOfSite = 'Authorization => Login';
        
        //- Validators -//
		$validators = array(
			'username'	=> array(
				'NotEmpty', 
				'EmailAddress'
			),
			'password'	=> array()
		);
		
		$input = new Zend_Filter_Input( array(), $validators );
			$input -> setData(
				$this -> getRequest() -> getParams()
			);
    	
		//- Get input data -//
		if( $this -> getRequest() -> isPost() )
		{
			//- Validate -//
			if( $input -> isValid() )
			{										
				//- Create adapter for authenticate -//
				Zend_Loader :: loadFile( 'Auth/Adapter.php' );
				
				$adapter = new Auth_Adapter( 
					$input -> username, 
					$input -> password 
				);
							 
				//- Authenticate -//
				$auth = Zend_Auth :: getInstance();
							
				if( $auth -> authenticate( $adapter ) -> isValid() )
				{
					//- User is authenticate -//
					$this -> session = new Zend_Session_Namespace( 'system.user' );
					
					//- Save data about current user in session -//
					$this -> session -> user = $adapter -> getUserData();
					
					//- Redirect to default -//
					$this -> _redirect( 
						'/' . strtolower( $this -> session -> user[ 'role' ][ 'title' ] ) 
					);
				}else
					{
						//- Display message: Error, authenticate -//
						$this -> errors[] = 'Error, Can not authentificate user. Repeat, please!';
					}
			}else
				{
					//- Data is not valid -//
					array_push( 
    					$this -> errors, 
    					'Error, data is not valid. Repeat, please!'
    				);
				}
		}
		
		//- Init view -//
		
    }

    public function logoutAction()
    {
        //- Destroy user session -//
		Zend_Auth :: getInstance() -> clearIdentity();
		Zend_Session :: destroy();
    }

    public function forgotAction()
    {
        // action body
    }

	//- Test -//
	public function testAction()
	{
		//- Filters -//
		$filters = array(
			'param'	=> array( 
				'HtmlEntities', 
				'StripTags', 
				'StringTrim'
			), 			
			'value'	=> array( 
				'HtmlEntities', 
				'StripTags', 
				'StringTrim'
			)
		);
		
		//- Validators -//
		$validators = array(
			'param'	=> array( 
		 		'NotEmpty', 
				'Alpha'
			), 
			'value'	=> array(
				'NotEmpty'
			)
		);
		
		//- Validation -//
		$input = new Zend_Filter_Input( $filters, $validators );
			$input -> setData(
				$this -> getRequest() -> getParams()
			);
			
		//- Test -//
		if( $this -> getRequest() -> isGet() )
		{
			if( $input -> isValid() )
			{
				switch( $input -> param )
				{
					//- Username -//
					case 'username':
					{
						$validator = new Zend_Validate_Alnum();
						
						if( $validator -> isValid( $input -> value ) )
						{
							//- Test in DB -//
							$response = Doctrine_Query :: create()
								-> from( "Jms_User" )
								-> where( "username = '{$input -> value}'" )
								-> limit( 1 );
								
							if( count( $response->fetchArray() ) === 0 )
							{
								//- Username are free -//
								$this -> view -> status = 1;
								$this -> view -> msg = 'Username is free';
							}else
								{
									//- Username are exist -//
									$this -> view -> status = 0;
									$this -> view -> msg = 'Username is exist';
								}
							
							return;
						}
						
					}break;
					
					//- E-mail -//
					case 'email':
					{
						$validator = new Zend_Validate_EmailAddress();
						
						if( $validator -> isValid( $input -> value ) )
						{
							//- Test in DB -//
							$response = Doctrine_Query :: create()
								-> from( "Jms_Email" )
								-> where( "address = '{$input -> value}'" )
								-> limit( 1 );
							
							if( count( $response->fetchArray() ) === 0 )
							{
								//- Username are free -//
								$this -> view -> status = 1;
								$this -> view -> msg = 'Email is free';
							}else
								{
									//- Username are exist -//
									$this -> view -> status = 0;
									$this -> view -> msg = 'Email is exist';
								}
							
							return;
						}
						
					}break;
					
					//- Journal ISBN -//
					case 'journalisbn':
					{
						$validator = new Zend_Validate_Regex( '/^[[:alnum:]\-]{2,15}$/uix' );
						
						if( $validator -> isValid( $input -> value ) )
						{
							//- Test in DB -//
							$response = Doctrine_Query :: create()
								-> from( "Jms_Journal" )
								-> where( "isbn = '{$input -> value}'" )
								-> limit( 1 );
							
							if( count( $response->fetchArray() ) === 0 )
							{
								//- Username are free -//
								$this -> view -> status = 1;
								$this -> view -> msg = 'Isbn is free';
							}else
								{
									//- Username are exist -//
									$this -> view -> status = 0;
									$this -> view -> msg = 'Isbn is exist';
								}
							
							return;
						}
						
					}break;
				}
			}
		}
		
		//- Username are exist -//
		$this -> view -> status = 0;
		$this -> view -> msg = 'Invalid input';
		//throw new Zend_Controller_Action_Exception( 'Invalid input' );
	}
}
