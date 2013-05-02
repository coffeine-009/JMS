<?php
///	***	Interface :: Messenger_Interface	***	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 *																	*
	 *	@copyright 2013
	 *			by														*
	 *	@author Vitaliy Tsutsman
	 *																	*
	 *	@date 2013-01-02 12:50:43 :: 2013-01-02 20:32:11
	 *																	*
	 *	Messenger_Interface ::											*
	 *		Prototype for send message									*
	 *																	*
	 *	@adress Ukraine/Ivano-Frankivsk
	 *																	*
 	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
interface Coffeine_Messenger_Interface
{
	///	***	Methods		***	///
	public function __construct(
		/*string*/	$AddressDsc,
		/*string*/	$Title,
		/*string*/	$Message
	);

	public function __destruct();

	public function init();// : bool

	//- SECTION :: GET -//
	//public function getTitle();
	//public function getMessage();

	public function setAddressSrc( /*string*/$Address );// : void
	public function setTemplate(
		/*string.path*/	$Template,
		/*string*/		$Template,
		/*array*/		$DataForTemplate
	);

	public function send();// : bool
}