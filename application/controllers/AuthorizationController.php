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
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    //- Registration action -//
    public function registrationAction()
    {
    	//- Array of arrays -//
    	$errors = array(
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
		    		$user -> id_role = 1;
		    		$user -> id_status = 1;
		    		
		    		$user -> first_name = $data[ 'first_name' ];
		    		$user -> second_name = $data[ 'second_name' ];
		    		$user -> father_name = $data[ 'father_name' ];

		    		$user -> gender = (int)$data[ 'gender' ];
		    		$user -> country = $data[ 'country' ];
		    		$user -> language = $data[ 'language' ];
		    	
		    	$user -> save();

		    	//- Send letter for activate -//
		    	$user -> sendActivationLatter();
		    	
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
					$errors[ 'username' ][] = $messageId . ': ' . $message;
				}
				
				//-  Password -//
				foreach( $vPassword -> getMessages() as $messageId => $message )
				{
					$errors[ 'password' ][] = $messageId . ': ' . $message;
				}
				
				//- Repeat passwrd -//
				if( $data[ 'password' ] !== $data[ 'repeat_password' ] )
				{
					$errors[ 'r_passwd' ][] = 'Passwords not idential';
				}				
				
				//- First name -//
				foreach( $vFirstName -> getMessages() as $messageId => $message )
				{
					$errors[ 'first_name' ][] = $messageId . ': ' . $message;
				}
				
				//- Last name -//
				foreach( $vSecondName -> getMessages() as $messageId => $message )
				{
					$errors[ 'last_name' ][] = $messageId . ': ' . $message;
				}
				
				//- Middle name -//
				foreach( $vFatherName -> getMessages() as $messageId => $message )
				{
					$errors[ 'middle_name' ][] = $messageId . ': ' . $message;
				}
				
				//- Gender -//
				foreach( $vGender -> getMessages() as $messageId => $message )
				{
					$errors[ 'gender' ][] = $messageId . ': ' . $message;
				}
				
				//- E-mail -//
				foreach( $vEmail -> getMessages() as $messageId => $message )
				{
					$errors[ 'email' ][] = $messageId . ': ' . $message;
				}
				
				//- Phone -//
				foreach( $vPhone -> getMessages() as $messageId => $message )
				{
					$errors[ 'phone' ][] = $messageId . ': ' . $message;
				}
				
				//- Skype -//
				foreach( $vSkype -> getMessages() as $messageId => $message )
				{
					$errors[ 'skype' ][] = $messageId . ': ' . $message;
				}
				
				//- Country -//
				foreach( $vAdress -> getMessages() as $messageId => $message )
				{
					$errors[ 'mailing_address' ][] = $messageId . ': ' . $message;
				}
				
				//- Country -//
				foreach( $vCountry -> getMessages() as $messageId => $message )
				{
					$errors[ 'country' ][] = $messageId . ': ' . $message;
				}
				
				//- Language -//
				foreach( $vLanguage -> getMessages() as $messageId => $message )
				{
					$errors[ 'language' ][] = $messageId . ': ' . $message;
				}
    		}
    	}
    	
    	
        //- Init view -//
        $this -> view -> Title = 'Registration';
        $this -> view -> pathOfSite = 'Main => Registration';
        
        $this -> view -> errors = $errors;
    }

    public function registrationsuccessAction()
    {
        // action body
    }

    public function loginAction()
    {
        // action body
    }

    public function logoutAction()
    {
        // action body
    }

    public function forgotAction()
    {
        // action body
    }


}











