<?php
///	***	Class :: Controller :: Authorization	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * 	@author Vitaliy Tsutsman
	 * 
	 * @date 2013-05-07 11:33:24 :: 2013-..-.. ..:..:..
	 * 
	 * @address Poland/Krakow/Bydruka/5/414
	 * 																*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Include other files	***	***	***	***	***	***	***	***	***	***	***	***	///
require_once APPLICATION_PATH . '/controllers/BaseController.php';

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class JournalController
	extends
		BaseController
{

    public function init()
    {
    	parent :: init();
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    //- List of journal -//
    public function listAction()
    {
    	//- Init view -//
        $this -> view -> Title = 'Journals';
        $this -> view -> pathOfSite = 'Jourmal => List';
        
    	//- Filters -//
    	$filters = array(
    		'page' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	), 
	    	'count' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	)
    	);
    	
    	//- Validators -//
    	$validators = array(
    		'page' => array( 
    			'Int' 
    		), 
    		'count' => array(
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
    		$page = (int)$input -> page;
	        $records_count = (int)$input -> count;
    		
	        //- Get list of journals -//
	        $query = Doctrine_Query :: create()
	        	-> from( 'Jms_Journal' )
	        	-> orderBy( 'id' );
	        	
	        //- Pager init -//
	        $pager = new Doctrine_Pager(
	        	$query, 
	        	$page,  
	        	$records_count
	        );
	        
	        //- Journals -//
	        $journals = $pager -> execute(
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
	        	'journal_list', 
	        	1
	        ) . "/{%page}/{$records_count}";
	        
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
	        $this -> view -> journals = $journals;
	        $this -> view -> pages = $pagerLayout -> display( null, true );
    	}else
    		{
    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
    		}
    }

	//- View -//
    public function viewAction()
    {
	    //- Init view -//
        //$this -> view -> Title = 'Journals';
        $this -> view -> pathOfSite = 'Jourmal => List';
        
    	//- Filters -//
    	$filters = array(
    		'id' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	), 
    		'page' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	), 
	    	'count' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	)
    	);
    	
    	//- Validators -//
    	$validators = array(
    		'id'	=> array(
    			'Int'
    		), 
    		'page' => array( 
    			'Int' 
    		), 
    		'count' => array(
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
    		$journal_id = (int)$input -> id;
    		$page = (int)$input -> page;
	        $records_count = (int)$input -> count;
    		
	        //- Get data about journal -//
	        $query = Doctrine_Query :: create()
	        	-> from( 'Jms_Journal j' )
	        	-> addFrom( 'j.JournalNumber jn' )
	        	-> where( 'id = ?', array( $journal_id ) )
	        	-> orderBy( 'id' );
	        	
	        //- Pager init -//
	        $pager = new Doctrine_Pager(
	        	$query, 
	        	$page,  
	        	$records_count
	        );
	        
	        //- Journals -//
	        $journal_data = $pager -> execute(
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
	        ) . "/{$journal_id}/{%page}/{$records_count}";
	        
	        //- Init template for display links -//
	        $pagerLayout = new Doctrine_Pager_Layout(
	        	$pager, 
	        	$pagerRange, 
	        	$pageUrlBase
	        );
	        
	        $pagerLayout -> setTemplate( '<a href = "{%url}">{%page}</a>' );
	        $pagerLayout -> setSelectedTemplate( '<span class = "current">{%page}</span>' );
	        $pagerLayout -> setSeparatorTemplate( '&nbsp' );
	        Zend_Debug::dump($journal_data);
	        //- Init view -//
	        $this -> view -> logotip = $journal_data[ 0 ][ 'title' ];
	        $this -> view -> journal = $journal_data[ 0 ];
	        $this -> view -> pages = $pagerLayout -> display( null, true );
    	}else
    		{
    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
    		}
    }
    
    //- Add new journal -//
    public function addAction()
    {
     	//- Init view -//
        $this -> view -> Title = 'Add journal';
        $this -> view -> pathOfSite = 'Jourmal => Add';
        
    	//- Filters -//
    	$filters = array(
    		'issn' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	), 
    		'title' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	), 
	    	'description' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	)
    	);
    	
    	//- Validators -//
    	$validators = array(
    		'issn'	=> array( 
    			'NotEmpty'
    		), 
    		'title' => array( 
    			'NotEmpty' 
    		), 
    		'description' => array(
    			'NotEmpty'
    		)
    	);
    	
    	if( $this -> getRequest() -> isPost() )
    	{
	    	$input = new Zend_Filter_Input( $filters, $validators );
	    		$input -> setData(
	    			$this -> getRequest() -> getParams()
	    		);
    	
	    	if( $input -> isValid() )
	    	{
	    		//- Save info -//
				$journal = new Jms_Journal();
		        	$journal -> issn = $input -> issn;
		        	$journal -> title = $input -> title;
		        	$journal -> description = $input -> description;
		        	
		        $journal -> save();
		        
		        //- Add message -//
		        $this -> _helper -> flashMessenger 
					-> addMessage( 'Journal is added.' );
		        
		        //- Redirect to view journal -//
		        $this -> _redirect( '/journal/' . $journal -> id );
	    	}else
	    		{
	    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
	    		}
    	}
    }

    public function editAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }


}