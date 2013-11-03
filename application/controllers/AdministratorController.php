<?php
///	***	Class :: Controller :: Authorization	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * 	@author Vitaliy Tsutsman
	 * 
	 * @date 2013-06-24 21:43:11 :: 2013-..-.. ..:..:..
	 * 
	 * @address Ukraine/Petranka/Gryshevskii/234
	 * 																*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Include other files	***	***	***	***	***	***	***	***	***	***	***	***	///
require_once APPLICATION_PATH . '/controllers/BaseController.php';

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class AdministratorController
	extends
		BaseController
{

    public function init()
    {
    	parent :: init();
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		//- Redirect to default page -//
		$this -> _redirect( '/article/add' );        
    }


}

