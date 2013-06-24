
$( document ).ready( function(){

	//- Ajac conection -//
	var connect = new Connect();
	
	//- Get handles -//
	var handles = {
		isbn	: document.getElementById( 'isbn' )
	};
	
	
	//- Set focus for default -//
	handles.isbn.focus();
	
	//- Set Ajax events for requests -//
	$( "#isbn" ).bind( 'keyup', function(){
		
		var timer = setInterval(
			function(){
				
				connect.setUrl( "/registration/test/journalisbn/" + handles.isbn.value );
				connect.setParams( "format=json" );				
				
				connect.Request(
					function( Response ){
						if( Response.status == 1 )
						{
							$( "#isbn-msg" ).html( "<font color = \"green\">" + Response.msg + "</font>" );
						}else
							{
								$( "#isbn-msg" ).html( Response.msg );
							}
					}
				);
				
				clearInterval( timer );
			}, 
			1500
		);
	} );
	
});