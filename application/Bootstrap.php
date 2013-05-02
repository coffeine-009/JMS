<?php
///	***	Bootstrap	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																	*
	 * @copyright 2013
	 * 		by
	 * @author Vitaliy Tsutsman
	 * 
	 * @date 2013-04-26 20:32:23 :: 2013-..-.. ..:..:..
	 * 
	 * @adress Ukraine/Petranka/Grushevskiy/234
	 * 
	 * @description
	 * 	Class :: Bootstrap
	 *																	*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class Bootstrap
	extends
		Zend_Application_Bootstrap_Bootstrap
{
	//- Routes -//
	public function _initRoute()
	{
		$router = Zend_Controller_Front :: getInstance() 
			-> getRouter();

		//- Get routes -//
		$config = new Zend_Config_Ini( 
			APPLICATION_PATH . '/configs/routes.ini', 
			'routes' 
		);

		//- Add routes to system -//
		$router -> addConfig( $config, 'routes' );
	}

	//- Navigation -//
	public function _initNavigation()
	{
		//- Get configuration of navigation -//
		$config = new Zend_Config_Ini( APPLICATION_PATH . '/configs/navigation.ini', 'guest' );
		
		//- Navigation container -//
		$container = new Zend_Navigation( $config );
		
		//- Registration -//
		Zend_Registry :: getInstance() -> set(
			'Zend_Navigation', 
			$container
		);
		
		//- Add helper -//
		require_once 'helpers/Navigation.php';
		Zend_Controller_Action_HelperBroker :: addHelper(
			new Application_Helpers_Navigation()
		);
	}
	
/*	
	///	*** Lacalization	***	///
	public function _initLocale()
	{
		//- Get support locales -//
		$locales = array( 'uk_UA', 'ru_RU' );//TODO
		
		//- Default locale -//
		$locale = null;
		
		//- Initialize session -//
		$session = new Zend_Session_Namespace( 'system.locale' );
		
		if( isset( $session -> locale ) )
		{
			//- Set user's locale -//
			$locale = new Zend_Locale( $session -> locale );
		}
		
		if( $locale === null )
		{
			try
			{
				$locale = new Zend_Locale( 'browser' );
				
				//- Test input locale -//
				if( !Zend_Validate :: is( 
						$locale, 
						'InArray', 
						array(
							$locales
						) 
					) 
				)
				{
					//- Generate exception? not supported language -//
					throw new Zend_Locale_Exception( 'Language is not support' );
				}
			}
			
			catch( Zend_Locale_Exception $Exception )
			{
				//- Set default language -//
				$locale = new Zend_Locale( 'uk_UA' );
			}
		}
		
		$session -> locale = $locale;
		
		//- Registration -//
		Zend_Registry :: getInstance()
			-> set( 'Zend_Locale', $locale );
	}
	
	
	//- String :: Translation -//
	public function _initTranslate()
	{
		$translate = new Zend_Translate( 
			'array', 
			APPLICATION_PATH . '/languages', 
			null, 
			array(
				'scan'				=> Zend_Translate :: LOCALE_FILENAME, 
				'disableNotices'	=> 1
			)
		);
		
		Zend_Registry :: getInstance() 
			-> set( 'Zend_Translate', $translate );
	}
*//*
	
	//- Acl -//
	public function _initAcl()
	{
		//- Load files -//
		Zend_Loader :: loadFile( APPLICATION_PATH . '/classes/Acl/Acl.php' );
			
		//- Registry plugin -//
		Zend_Registry :: set( 'acl', new Acl_Acl() );
	}
	*/
/*
	//- DataBase -//
	public function _initDataBase()
	{		
		//- Get adapter -//
		$db = $this -> getPluginResource( 'db' ) -> getDbAdapter();

		//- Set default adapter -//
		//Zend_Db_Table :: setDefaultAdapter( $db );
		
		//- Registry adapter -//
		Zend_Registry :: set( 'db', $db );
	}
	
	
	//- Plugins -//
	public function _initPlugins()
	{
		//- Load files -//
		Zend_Loader :: loadFile( APPLICATION_PATH . '/classes/Acl/Acl.php' );
		Zend_Loader :: loadFile( APPLICATION_PATH . '/plugins/CheckAccess.php' );
				
		//- Register plugin -//
		Zend_Controller_Front :: getInstance() 
		-> registerPlugin( 
			new CheckAccess( 
				new Acl_Acl() 
			) 
		);
	}
*/
	//- Doctrine -//
	public function _initDoctrine()
	{
		require_once 'Doctrine/Doctrine.php';
		
		//- Add doctrine autoloader -//
		$this -> getApplication()
			-> getAutoloader()
			-> pushAutoloader(
				array(
					'Doctrine', 
					'autoload'
				), 
				'Doctrine'
			);

		//- Init manager -//
		$mannager = Doctrine_Manager :: getInstance();
			$mannager -> setAttribute(
				Doctrine :: ATTR_MODEL_LOADING, 
				Doctrine :: MODEL_LOADING_CONSERVATIVE
			);
			
		//- Configuration -//
		$config = $this -> getOption( 'resources' );
		$dsn = 'mysql://' . 
			$config[ 'db' ][ 'params' ][ 'username' ] . ':' . 
			$config[ 'db' ][ 'params' ][ 'password' ] . '@' . 
			$config[ 'db' ][ 'params' ][ 'host' ] . '/' . 
			$config[ 'db' ][ 'params' ][ 'dbname' ];
		
		return Doctrine_Manager :: connection(
			$dsn, 
			'doctrine'
		);
	}
}

