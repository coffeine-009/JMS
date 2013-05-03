<?php
///	***	Class :: Messenger_SMS	***	***	***	***	***	***	***	***	***	***	***	///

/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
 *																	*
 *	@copyright 2012
 *			by														*
 *	@author Vitaliy Tsutsman
 *																	*
 *	@date 2013-01-02 14:43:48 :: 2013/01/.. ..:..:..
 *																	*
 *	Messenger_SMS ::												*
 *		Class for send message.										*
 *																	*
 *	@adress Ukraine/Ivano-Frankivsk
 *																	*
 *///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class Coffeine_Messenger_SMS_Main
implements
Coffeine_Messenger_Interface
{
	//- Constants -//
	const SOAP_API_ADAPTER = '/api/wsdl.html';

	///	***	Properties	***	///
	protected $addressSrc;
	protected $addressDsc;

	protected $subject;
	protected $message;

	protected $template;


	protected $link;
	protected $config;

	///	***	Methods		***///
	public function __construct(
	/*string*/	$AddressDsc,
	/*string*/	$Subject,
	/*string*/	$Message
	)
	{
		$this -> addressDsc 	= $AddressDsc;
		$this -> subject 		= $Subject;
		$this -> message 		= $Message;

		//- Read configuration -//
		$this -> config = new Zend_Config_Ini( 'configs/tools.ini', 'resources' );

		//- Connect to SMS server -//
		$this -> link = new SoapClient(
		$this -> config -> resources -> sms -> host .
		self :: SOAP_API_ADAPTER
		);
	}

	public function __destruct()
	{
		$this -> addressDsc 	= null;
		$this -> messageTitle 	= null;
		$this -> message 		= null;

		$this -> link = null;
		$this -> config = null;
	}

	//- Initialize server -//
	public function init()// : bool
	{return true;
	//- Init -//
	$this -> addressSrc = $this -> config -> resources -> sms -> params -> from;

	//- Set default transport -//
	if( 'Вы успешно авторизировались' == $this -> link -> Auth(
	array(
					'login' 	=> $this -> config -> resources -> sms -> params -> username, 
					'password' 	=> $this -> config -> resources -> sms -> params -> password 
	)
	) -> AuthResult
	)
	{
		//- SUCCESS :: AUTH -//
		return true;
	}

	return false;
	}

	//- SECTION :: SET -//
	//- Set address from -//
	public function setAddressSrc( /*string*/$Phone )// : void
	{
		$this -> addressSrc = $Phone;
	}

	//- Message template -//
	public function setTemplate(
	/*string.path*/	$TemplatePath,
	/*string*/		$Template,
	/*array*/		$DataForTemplate
	)// : void
	{
		$this -> template = $Template;

		//- Create message -//

		$this -> message = '';
	}

	//- Send message -//
	public function send()// : bool
	{return true;
	//- Init message and send -//
	if( 'Сообщения успешно отправлены' == $this -> link
	-> sendSMS(
	array(
						'sender'		=> $this -> addressSrc, 
						'destination'	=> $this -> addressDsc, 
						'text'			=> $this -> message
	)
	) -> SendSMSResult -> ResultArray[ 0 ]
	)
	{
		//- SUCCESS :: OK -//
		return true;
	}

	//- ERROR :: Message not sent -//
	return false;
	}
}