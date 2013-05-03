
$( document ).ready( function(){

	//- Ajac conection -//
	var connect = new Connect();
	
	//- Get handles -//
	var handles = {
		username	: document.getElementById( 'username' ), 
		email		: document.getElementById( 'email' )
	};
	
	
	//- Set focus for default -//
	handles.username.focus();
	
	//- Set Ajax events for requests -//
	$( "#username" ).bind( 'keyup', function(){
		
		var timer = setInterval(
			function(){
				
				connect.setUrl( "/registration/test/username/" + handles.username.value );
				connect.setParams( "format=json" );				
				
				connect.Request(
					function( Response ){
						if( Response.status == 1 )
						{
							$( "#username-msg" ).html( Response.msg );
						}else
							{
								$( "#username-msg" ).html( Response.msg );
							}
					}
				);
				
				clearInterval( timer );
			}, 
			1500
		);
	} );
	
	//- E-mail -//
	$( "#email" ).bind( 'keyup', function(){
		
		var timer = setInterval(
			function(){
				
				connect.setUrl( "/registration/test/email/" + handles.email.value );
				connect.setParams( "format=json" );				
				
				connect.Request(
					function( Response ){
						if( Response.status == 1 )
						{
							$( "#email-msg" ).html( Response.msg );
						}else
							{
								$( "#email-msg" ).html( Response.msg );
							}
					}
				);
				
				clearInterval( timer );
			}, 
			1500
		);
	} );
	
});