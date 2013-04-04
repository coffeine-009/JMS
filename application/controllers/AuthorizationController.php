<?php
///	***	Class :: Controller :: Authorization	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * 	@author Vitaliy Tsutsman
	 * 
	 * @date 2013-04-04 17:48:46 :: 
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
    	//- Get data from form -//
    	
    	
        //- Init view -//
        $this -> view -> Title = 'Registration';
        $this -> view -> pathOfSite = 'Main => Registration';
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











