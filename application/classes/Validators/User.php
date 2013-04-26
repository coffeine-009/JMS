<?php
/**
 * 
 * @date 2013-01-05 16:07:15 :: 2013-01-05 17:14:02
 */

class Validators_User
{
	///	***	Properties	***	///
	protected $firstName;
	
	protected $phone;
	protected $email;
	
	protected $report;
	
	//--//
	protected $valid;
	
	
	///	***	Methods		***	///
	public function __construct( 
		/*string*/	$FirstName, 
		/*string*/	$Email, 
		/*string*/	$Phone = null, 
		/*array.string*/$Report = null
	)
	{
		$vEmail = new Zend_Validate();
    		$vEmail -> addValidator( new Zend_Validate_NotEmpty )
    			-> addValidator( new Zend_Validate_EmailAddress );
    					
    	$vName = new Zend_Validate();
    		$vName -> addValidator( new Zend_Validate_NotEmpty )
    			-> addValidator( new Zend_Validate_Alpha );
    			
    	$vPhoneMobile = new Zend_Validate_Regex( 
    		array( 
    			'pattern' => '/^\+?\d{1,3}\ ?\d{2,4}\ ?(\d{5,7}|\-)$/i' 
    		) 
    	);
    	
    	$vConst = new Zend_Validate_Regex( 
    		array( 
    			'pattern' => '/^[\-\w]+$/i' 
    		) 
    	);

    	//- Validate -//
    	$this -> valid = true 
	    	&& $vName -> isValid( $FirstName )
	    	&& $vEmail -> isValid( $Email )
	    	&& (empty( $Phone ) || $vPhoneMobile -> isValid( $Phone ));
	    foreach( $Report as $report )
	    {
	    	$this -> valid = $this -> valid 
	    		&& $vConst -> isValid( $report );
	    }
	    	
	    //- Filter Data -//
	    $this -> firstName = $FirstName;
	    $this -> email = $Email;
	    
	    
	    $len = strlen( $Phone ); 
	    if( $len > 0 )
	    {
		    $mob = '';
		    for( $i = 0; $i < $len; $i++ )
		    {
		    	if( $Phone[ $i ] >= '0' && $Phone[ $i ] <= '9' )
		    	{
		    		$mob .= $Phone[ $i ];
		    	}
		    }
		    $this -> phone = $mob;
	    }else
	    	{
	    		$this -> phone = null;
	    	}
	    	    
	    //- Report type -//
    	$tmp_report_title = ''; $ti = 0;
    	if( count( $Report ) > 0 ){
    		foreach( $Report as $rt ){
    			if( $ti != 0 ){ $tmp_report_title .= ' and '; }
    			$tmp_report_title .= $rt;
    			$ti++;
    		}
    	}
    	$this -> report = new Orenda_Report_Type( null, $tmp_report_title );
    		$this -> report -> read();
	}
	
	public function __destruct()
	{
		$this -> firstName = null;
		$this -> email = null;
		$this -> phone = null;
		
		$this -> report = null;
		
		$this -> valid = null;		
	}
	
	//- Valid -//
	public function isValid()// : bool
	{
		return $this -> valid;
	}
	
	//- Res -//
	public function getUserObj()
	{
		return (object)array(
			'firstName'	=> $this -> firstName, 
			'email'		=> $this -> email, 
			'phone'		=> $this -> phone, 
			'report_type'=>$this -> report -> getId()
		);
	}
}