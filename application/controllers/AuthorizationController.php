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
    		'username'	=> '', 
    		'password'	=> '', 
    		'r_passwd'	=> '', 
    		'gender'	=> '', 
    		//- Names -//
    		'first_name'	=> '', 
    		'last_name'		=> '', 
    		'middle_name'	=> '',
    		//- Contacts -//
    		'email'				=> '', 
    		'phone'				=> '', 
    		'skype'				=> '', 
    	 	'mailing_address'	=> '', 
    		'country'			=> '', 
    		//- Params -//
    		'params'	=> ''
    	);
    	
    	//- Get data from form -//
    	if( $this -> getRequest() -> isPost() )
    	{
    		$data = $this -> getRequest() -> getParams();
    		
    		
    		//- Validation -//
			$vUserName = new Zend_Validate_Alnum();
	
			if( $vUserName -> isValid( $data[ 'username' ] ) )
			{
				// email appears to be valid
				echo 'ok';
			}else
			{
				// email is invalid; print the reasons
				foreach( $vUserName -> getMessages() as $messageId => $message )
				{
					echo "Validation failure '$messageId': $message\n";
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











