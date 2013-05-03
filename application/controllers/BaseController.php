<?php

	/**
	 * 
	 * @author coffeine
	 * @date 2013-04-04 17:38:13 
	 */

class BaseController
	extends
		Zend_Controller_Action
{
	///	***	Properties	***	///
	protected $session;
	
	protected $errors;
	
	///	***	Methods		***	///
	public function init()
	{
		//- Test user -//
		$this -> session = new Zend_Session_Namespace( 'system.user' );
		
		if( Zend_Auth :: getInstance() -> hasIdentity() )
		{
			$this -> view -> user = $this -> session -> user;
		}
		
		//- Init -//
		$this -> errors = array();
	}
	
	
	public function postDispatch()
	{
		//- Init view -//
		$this -> view -> errors = $this -> errors;
	}
}