<?php
///	***	Class :: Messenger_E-mail	***	***	***	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 *																	*
	 *	@copyright 2013
	 *			by														*
	 *	@author Vitaliy Tsutsman
	 *																	*
	 *	@date 2013-01-02 13:06:38 :: 2013-01-02 14:43:34
	 *																	*
	 *	Messenger_Mail ::												*
	 *		Class for send message.										*
	 *																	*
	 *	@adress Ukraine/Ivano-Frankivsk
	 *																	*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class Coffeine_Messenger_Mail_Main
	implements
		Coffeine_Messenger_Interface
{
	///	***	Properties	***	///
	protected $addressSrc;
	protected $addressDsc;

	protected $subject;
	protected $message;

	protected $template;


	protected $linkMail;

	///	***	Methods		***///
	public function __construct(
		/*string*/	$AddressDsc,
		/*string*/	$Subject = '',
		/*string*/	$Message = ''
	)
	{
		$this -> addressDsc 	= $AddressDsc;
		$this -> subject 		= $Subject;
		$this -> message 		= $Message;

		//--//
		$this -> linkMail = new Zend_Mail( 'UTF-8' );
	}

	public function __destruct()
	{
		$this -> addressDsc 	= null;
		$this -> messageTitle 	= null;
		$this -> message 		= null;
	}

	//- Initialize server -//
	public function init()// : bool
	{
		//- Read configuration -//
		$config = new Zend_Config_Ini( 'configs/application.ini', 'production' );
		//- Init -//
		$this -> addressSrc = $config -> resources -> mail -> smtp -> params -> emailFrom;

		//- Set default transport -//
		Zend_Mail :: setDefaultTransport(
			new Zend_Mail_Transport_Smtp(
				$config -> resources -> mail -> smtp -> host,
				array(
					'auth'		=> 'login',
					'ssl' 		=> 'ssl',
					'username' 	=> $config -> resources -> mail -> smtp -> params -> username, 
					'password' 	=> $config -> resources -> mail -> smtp -> params -> password, 
					'port' 		=> 465
				)
			)
		);
	}

	//- SECTION :: SET -//
	//- Set address from -//
	public function setAddressSrc( /*string*/$Email )// : void
	{
		$this -> addressSrc = $Email;
	}

	//- Message template -//
	public function setTemplate( 
		/*string.path*/	$TemplatePath, 
		/*string.file*/	$Template, 
		/*array*/		$DataForTemplate 
	)// : void
	{
		$this -> template = $Template;

		//- Create message -//
		$emailTemplate = new Zend_View();
		$emailTemplate -> setScriptPath(
			//APPLICATION_PATH . '/modules/default/views/emails/'
			$TemplatePath
		);
		$emailTemplate -> assign( $DataForTemplate );

		$this -> message = $emailTemplate -> render( $this -> template );
	}

	//- Send message -//
	public function send()// : bool
	{
		//- Send Message -//
		try
		{
			//- Init message and send -//
			$this -> linkMail
				-> setFrom(
					'info@orenduem.com.ua', 
					'orenduem.com.ua' 
				)
				-> addTo( $this -> addressDsc )
				-> setSubject( $this -> subject )
				-> setBodyHtml(
					$this -> message,
					'UTF-8', 
					Zend_Mime :: ENCODING_BASE64
				)

				-> send();
		}
		catch( Exception $Exc )
		{
			//- ERROR :: Message not sent -//
			return false;
		}

		//- SUCCESS :: OK -//
		return true;
	}
}