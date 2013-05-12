<?php
///	***	Class :: Controller :: JournalNumber	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * 	@author Vitaliy Tsutsman
	 * 
	 * @date 2013-05-12 14:10:28 :: 2013-..-.. ..:..:..
	 * 
	 * @address Poland/Krakow/Bydruka/5/414
	 * 																*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Include other files	***	***	***	***	***	***	***	***	***	***	***	***	///
require_once APPLICATION_PATH . '/controllers/BaseController.php';

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class JournalnumberController
	extends
		//Zend_Controller_Action
		BaseController
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function listAction()
    {
    	//- Init view -//
        $this -> view -> Title = 'Numbers of journal';
        $this -> view -> pathOfSite = 'Jourmal => List';
        
    	//- Filters -//
    	$filters = array(
    		'*' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	)
    	);
    	
    	//- Validators -//
    	$validators = array(
    		'*'		=> array(
    			'NotEmpty'
    		), 
    		'id'	=> array(
    			'Int'
    		)
    	);
    	
    	$input = new Zend_Filter_Input( $filters, $validators );
    		$input -> setData(
    			$this -> getRequest() -> getParams()
    		);
    	
    	if( $input -> isValid() )
    	{
    		//- Data -//
    		$journal_number_id = (int)$input -> id;
    		$page = (int)$input -> page;
	        $records_count = (int)$input -> count;
    		
	        //- Get data about journal -//
	        $query = Doctrine_Query :: create()
	        	-> from( 'Jms_JournalNumber jn' )
	        	-> addFrom( 'jn.Journal j' )
	        	-> where( 'j.id = ?', array( $journal_number_id ) )
	        	-> orderBy( 'j.id' );
	       	
	        //- Pager init -//
	        $pager = new Doctrine_Pager(
	        	$query, 
	        	$page,  
	        	$records_count
	        );
	        
	        //- Journals -//
	        $journal_number_data = $pager -> execute(
	        	array(), 
	        	Doctrine :: HYDRATE_ARRAY
	        );
	        
	        //- Init maket for pager -//
	        $pagerRange = new Doctrine_Pager_Range_Sliding(
	        	array(
	        		'chunk' => $records_count
	        	), 
	        	$pager
	        );
	        
	        //- Set base url -//
	        $pageUrlBase = $this -> view -> url(
	        	array(), 
	        	'journal_view', 
	        	1
	        ) . "/{$journal_number_id}/{%page}/{$records_count}";
	        
	        //- Init template for display links -//
	        $pagerLayout = new Doctrine_Pager_Layout(
	        	$pager, 
	        	$pagerRange, 
	        	$pageUrlBase
	        );
	        
	        $pagerLayout -> setTemplate( '<a href = "{%url}">{%page}</a>' );
	        $pagerLayout -> setSelectedTemplate( '<span class = "current">{%page}</span>' );
	        $pagerLayout -> setSeparatorTemplate( '&nbsp' );
			
	        //- Init view -//
	        $this -> view -> logotip = $journal_number_data[ 0 ][ 'Journal' ][ 'title' ];
	        $this -> view -> journal = $journal_number_data[ 0 ][ 'Journal' ];
	        $this -> view -> journalNumbers = $journal_number_data;
	        $this -> view -> pages = $pagerLayout -> display( null, true );
    	}else
    		{
    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
    		}
    }

    public function addAction()
    {
        // action body
    }

    public function editAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }

    public function viewAction()
    {
        // action body
    }


}
