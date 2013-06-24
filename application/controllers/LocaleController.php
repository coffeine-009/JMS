<?php
///	***	Class :: Controller :: Journal	***	***	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * @author Vitaliy Tsutsman
	 * 
	 * @date 2013-06-24 15:08:47 :: 2013-06-24 16:53:21
	 * 
	 * @address Ukraine/Petranka/Gryshevskiy/234
	 * 																*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Include other files	***	***	***	***	***	***	***	***	***	***	***	***	///
//require_once APPLICATION_PATH . '/controllers/BaseController.php';

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class LocaleController
	extends
		Zend_Controller_Action
{
	///	***	Properties	***	///
	protected $session;

	
	///	***	Methods		***	///
	//- Initialize -//
	public function init()
	{
		$this -> session = new Zend_Session_Namespace( 'system.locale' );
	}

	//- Change new locale -//
	public function changeAction()
	{
		//- Get support locales -//
		$locales = array( 'en_GB', 'en_US', 'uk_UA', 'ru_RU' );

		//- Test input locale -//
		if( 
			Zend_Validate :: is(
				$this -> getRequest() -> getParam( 'locale' ),
				'InArray', 
				array(
					$locales
				)
			)
		)
		{
			//- Set new locale -//
			$this -> session -> locale = $this -> getRequest() -> getParam( 'locale' );
		}else
			{
				$this -> session -> locale = new Zend_Locale( 'en_GB' );
			}


		//- Redirect to user url -//
		$this -> _redirect(
			$this -> getRequest() -> getServer( 'HTTP_REFERER' )
		);
	}
}
