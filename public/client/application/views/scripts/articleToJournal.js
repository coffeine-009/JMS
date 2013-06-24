
$( document ).ready( function(){
	
	//- Ajac conection -//
	var connect = new Connect();
	
	//- Get handles -//
	var handles = {
		jid		: document.getElementById( 'jid' ),
		volume	: document.getElementById( 'jn_volume' )
	};
	
	//- Load volumes of selected journal -//
	$( "#journal" ).change(function(){
		
		var jid = handles.jid.value;
		
		connect.setUrl( "/journal/" + jid + "/numbers/get" );
		connect.setParams( "format=json" );
		
		connect.Request(
			function( Response ){
				if( Response.status == 1 )
				{
					//- Set volume -//
					var volumes = "";
					for( var i = 0; i < Response.data.length; i++ )
					{
						//- Add volume -//
						volumes += "<option value = '" + Response.data[ i ].id + "'>" + 
							"volume: " + Response.data[ i ].volume + 
							", issue: " + Response.data[ i ].issue + 
							"</option>";
						
						handles.volume.innerHTML = volumes;
					}
				}else
					{
						$( "#errors" ).html( 
							"<ul><li>" + Response.msg + "</li></ul>"
						);
					}
			}
		);
	});
	
});
