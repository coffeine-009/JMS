<?php
///	***	Class :: Controller :: Index	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * 	@author Vitaliy Tsutsman
	 * 
	 * @date 2013-05-07 10:58:12 :: 2013-..-.. ..:..:..
	 * 
	 * @address Poland/Krakow/Bydryka/5/414
	 * 																*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Include other files	***	***	***	***	***	***	***	***	***	***	***	***	///
require_once APPLICATION_PATH . '/controllers/BaseController.php';

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class IndexController
	extends
		//Zend_Controller_Action
		BaseController
{

    public function init()
    {
		parent :: init();
    }

    public function indexAction()
    {
        //TODO
    }    
}

