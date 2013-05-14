<?php
///	***	Class :: Parser :: Analyzer	***	***	***	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * 	@author Vitaliy Tsutsman
	 * 
	 * @date 2013-05-17 17:32:20 :: 2013-05-17 21:19:40
	 * 
	 * @address Poland/Krakow/Bydruka/5/414
	 * 																*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*


///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class Jms_Parser_Analyzer
{
	///	***	Propertties	***	///
	protected $text;	//- Text for seaching 		-//
	protected $value;	//- Result, finded commands -//
	
	//-# Params #-//
	protected $options;	//- Options of commands -//
	protected $errors;	//- Error messages 		-//	
	
	//- Validation -//
	protected $valid;
	
	
	///	***	Methods		***	///
	///	***	Methods		***	///
	public function __construct(
		/*string*/	$Text, 
		/*array*/	$Options = null
	)
	{
		//- Init -//
		$this -> text = $Text;
		$this -> options = $Options;
		
		$this -> errors = array();
		$this -> value = array();
		
		$this -> valid = true;
	}
	
	public function __destruct()
	{
		//- Free memory -//
		$this -> text = null;
		$this -> options = null;
		
		$this -> errors = null;
	}
	
	
	//-# SECTION :: SET #-//
	//- Options -//
	public function setOptions( /*array*/$Options )// : void
	{
		$this -> options = $Options;
	}
	
	//- Text -//
	public function setText( /*string*/$Text )// : void
	{
		$this -> text = $text;
	}
	
	
	//-# SECTION :: GET #-//
	//- Errors -//
	public function getErrors()// : array.asotiation
	{
		return $this -> errors;
	}
	
	//- Value -//
	public function getValue()// : array.asotiation
	{
		return $this -> value;
	}
	
	
	//-# Functional #-//
	//- Parse -//
	public function parse()// : void
	{
		foreach( $this -> options[ 'command' ] as $command => $params )
		{
			//- Parsing -//
			$result = null;
			preg_match_all(
				$params[ 'regex' ], 
				$this -> text, 
				$result, 
				PREG_PATTERN_ORDER
			);

			//- Set result -//
			$this -> value[ $command ] = array();
			$this -> errors[ $command ] = array();
			foreach( $params[ 'value' ] as $key => $index )
			{
				if( !isset( $result[ $index ][ 0 ] ) )
				{
					$this -> errors[ $command ][ $key ] = "Command not valid";//TODO: change
					
					$this -> valid = false;
					break;
				}
				
				//- Set value -//
				$this -> value[ $command ][ $key ] = $result[ $index ][ 0 ];
			}			
		}
	}
	
	//- Validation -//
	public function isValid()// : bool
	{
		return $this -> valid;
	}
}
