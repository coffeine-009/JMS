<?php
///	***	Class :: Helper.Navigation	***	***	***	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																	*
	 * @copyright 2013
	 * 		by
	 * @author Vitaliy Tsutsman
	 * 
	 * @date 2013-05-02 23:42:38 :: 2013-05-02 23:42:52
	 * 
	 * @adress Ukraine/Petranka/Grushevskiy/234
	 * 
	 * @description
	 * 	Class :: Helper.Navigation
	 *																	*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class Application_Helpers_Navigation
	extends
		Zend_Controller_Action_Helper_Abstract
{
	///	***	Properties	***	///
	protected $_container;
	
	
	///	***	Methods		***	///
	public function __construct( Zend_Navigation $Container = null )
	{
		if( null !== $Container )
		{
			$this -> _container = $Container;
		}
	}
	
	public function preDispatch()
	{
		//- Activate current uri -//
		$this -> getContainer()
			-> findBy( 'uri', $this -> getRequest() -> getRequestUri() )
			-> active = true;
	}
	
	public function getContainer()
	{
		if( null === $this -> _container )
		{
			$this -> _container = Zend_Registry :: get( 'Zend_Navigation' );
		}
		
		if( null === $this -> _container )
		{
			throw new  RunTimeException( 'Navigation container unavailable' );
		}
		
		return $this -> _container;
	}
}