<?php

/**
 * 
 *	@date 2013-01-05 15:00:00 :: 2013-01-05 15:39:27
 */

class Validator_Request
{
	///	***	Properties	***	///
	protected $valid;
	
	protected $idCity;
	protected $boroughs;
	protected $abodeTypes;
	protected $idValuta;
	protected $price;
	
	protected $rentType;
	
	protected $rentTimeFrom;
	protected $rentTimeTo;
	
	protected $peopleCount;
	protected $peopleCountMore;
	
	protected $children;
	protected $pets;
	
	protected $student;
	protected $foreign;
	
	protected $comment;
	
	protected $timeLimit;
	
	//-  -//
	protected $vDateTime;
	protected $fText;
	
	///	***Methods		***	///
	public function __construct(
		/*int*/			$IdCity, 
		/*array.int*/	$IdBoroughs, 
		/*array.int*/	$IdAbodeTypes, 
		/*int*/			$IdValuta, 
		/*float*/		$Price, 
		//- Rent :: Day and night -//
		/*TIMESTAMP*/	$dRentTimeFrom, 
		/*TIMESTAMP*/	$dRentTimeTo, 
		/*string*/		$dPeopleCount, 
		/*string*/		$dComment, 
		//- Rent :: Month -//
		/*TIMESTAMP*/	$mRentTime, 
		/*string*/		$mChildren, 
		/*string*/		$mPets, 
		/*bool*/		$mStudent, 
		/*bool*/		$mForeigner, 
		/*string*/		$mPeopleCount, 
		/*string*/		$mComment, 
		/*TIMESTAMP*/	$TimeLimit
	)
	{
		//- Create validators -//
		$this ->  vDateTime = new Zend_Validate();
    		$this ->  vDateTime -> addValidator( new Zend_Validate_NotEmpty() )
    		-> addValidator( new Zend_Validate_Date( 'YYYY-MM-DD' ) );
    		
    	//- Create filter -//
    	$this -> fText = new Zend_Filter_HtmlEntities();
    	
    	//- Default -//
    	$this -> valid = false;
    	
		//- Initialize -//
		$this -> idCity 	= (int)$IdCity;
		$this -> boroughs 	= ( $IdBoroughs ) ? $this -> ArrayInt( $IdBoroughs ) : null;
		$this -> abodeTypes = ( $IdAbodeTypes ) ? $this -> ArrayInt( $IdAbodeTypes ) : null;
		$this -> idValuta 	= (int)$IdValuta;
		$this -> price 		= (float)$Price;
		
		$this -> rentType = new Orenda_Rent_Type();
		
		//- Select type of rent -//
    	if( $this -> vDateTime -> isValid( $dRentTimeFrom )
    			&& 
    		$this -> vDateTime -> isValid( $dRentTimeTo )
    	)
    	{
    		//- Rent :: Day and night -//
    		$this -> rentType -> setTitle( 'Day and night' );
	    		$this -> rentTimeFrom = $dRentTimeFrom . ' 00:00:00';
	    		$this -> rentTimeTo = $dRentTimeTo . ' 00:00:00';
    			
	    	$tmp_from = new Zend_Date( $this -> rentTimeFrom );
	    	$tmp_to = new Zend_Date( $this -> rentTimeTo );
	    	if( $tmp_from -> compare( $tmp_to ) != -1 )
	    	{
	    		//- Error :: Bad Rent Date -//
	    		$this -> valid = false;
	    	}
	    
    		$this -> peopleCount = (int)$dPeopleCount;
    		if( substr( $dPeopleCount, -1 )  == '+' )
    		{
    			$this -> peopleCount = (int)substr( $dPeopleCount, 0, -1 );
    			$this -> peopleCountMore = true;
    		}
    		
    			$this -> children = null;
		    	$this -> pets = null;
		    	
		    	$this -> student = 0;
		    	$this -> foreign = 0;
    		
    		$this -> comment = $this -> fText -> filter( $dComment );
    		
    		$this -> valid = true;
    	}else
    		{
    			//- Rent :: Month -//
				$this -> rentType -> setTitle( 'Month' );
    				
				if( substr( $mRentTime, 0, 1 ) == '-' )
				{
					$this -> rentTimeFrom = null;
					$this -> rentTimeTo = substr( $mRentTime, 1 );
				}
    			if( substr( $mRentTime, -1 ) == '+' )
				{
					$this -> rentTimeFrom = substr( $mRentTime, 0, -1);
					$this -> rentTimeTo = null;
				}
					
		    	$this -> peopleCount = (int)$mPeopleCount;
		    	if( substr( $mPeopleCount, -1 )  == '+' )
		    	{
		    		$this -> peopleCount = (int)substr( $mPeopleCount, 0, -1 );
		    		$this -> peopleCountMore = true;
		    	}
		    	
		    	$this -> children = $this -> fText -> filter( $mChildren );
		    	$this -> pets = $this -> fText -> filter( $mPets );
		    	
		    	$this -> student = (int)$mStudent;
		    	$this -> foreign = (int)$mForeigner;
		    	
		    	$this -> comment = $this -> fText -> filter( $mComment );
		    	
		    	$this -> valid = true;
    		}
    		
    	$this -> timeLimit = $TimeLimit . ' 00:00:00';
    		
    	$this -> rentType -> read();
    	
    	//- Validate -//
    	$td = new Zend_Validate_Regex( 
    		array( 
    			'pattern' => '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/i' 
    		) 
    	);
    	
    	$this -> valid = $this -> valid
    		&& $td -> isValid( $this -> timeLimit ) ;
	}
	
	public function __destruct()
	{
		//- Free memory -//
		$this -> idCity = null;
		$this -> boroughs = null;
		$this -> abodeTypes = null;
		$this -> idValuta = null;
		$this -> price = null;
		$this -> rentType = null;
		$this -> rentTimeFrom = null;
		$this -> rentTimeTo = null;
		$this -> peopleCount = null;
		$this -> peopleCountMore = null;
		$this -> children = null;
		$this -> pets = null;
		$this -> student = null;
		$this -> foreign = null;
		$this -> comment = null;
		$this -> timeLimit = null;
		$this -> vDateTime = null;
		$this -> fText = null;
	}
	
	//- Valid -//
	public function isValid()// : bool;
	{
		return $this -> valid;
	}
	
	//-  -//
	public function getRequestObj()// : obj
	{
		return (object)array(
			'idCity'		=> $this -> idCity, 
			'boroughs'		=> $this -> boroughs, 
			'abodeTypes'	=> $this -> abodeTypes, 
			'idValuta'		=> $this -> idValuta, 
			'price'			=> $this -> price,
		 			
			'idRentType'	=> $this -> rentType -> getId(), 
			
			'rentTimeFrom'	=> $this -> rentTimeFrom, 
			'rentTimeTo'	=> $this -> rentTimeTo, 
			
			'peopleCount'	=> $this -> peopleCount, 			
			'peopleCountMore'=> $this -> peopleCountMore, 
			
			'children'		=> $this -> children, 
			'pets'			=> $this -> pets, 
			
			'student'		=> $this -> student, 
			'foreign'		=> $this -> foreign, 
			
			'comment'		=> $this -> comment, 
			
			'timeLimit'		=> $this -> timeLimit
		);
	}
	
	//- Array.int -//
	public function ArrayInt( /*array*/$Array )// : void
	{
		$result = array();
		
		if( is_array( $Array ) )
		{
			foreach( $Array as $elem )
			{
				array_push( $result, (int)$elem );
			}
		}
		
		return $result;
	}

	//- Report type -//
	public static function getReportType( /*array*/$ReportType )// : int
	{
		//- Report type -//
    	$tmp_report_title = ''; $ti = 0;
    	if( count( $ReportType ) > 0 ){
    		foreach( $ReportType as $rt ){
    			if( $ti != 0 ){ $tmp_report_title .= ' and '; }
    			$tmp_report_title .= $rt;
    			$ti++;
    		}
    	}
    	$report = new Orenda_Report_Type( null, $tmp_report_title );
    		    	
    	return ( $report -> read() ) ? $report -> getId() : null;
	}
}