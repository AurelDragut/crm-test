

/*  jquery.ui.kdcomplete MANIPULATION widget */
	$.widget( "custom.kdcomplete", $.ui.autocomplete, {
		_renderItem: function( ul, item ) {
			return $( '<li></li>' )
				.data( "item.autocomplete", item )
				.append( '<a style="color: #0475EA;" class="' + item.cArtNr + ' "> <b>' + item.cName + ' </b></a>' )
				.appendTo( ul );
		}});     
	
            
			
$(document).ready(function() {

/* Kundensuche input#kdsearch	*/
 	$(function() {
		var cache = {},
			lastXhr;
	$(".machine").focus(function(){
	
 		$(this).kdcomplete({
			minLength: 2,
			/*source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response( cache[ term ] );
					return;
				}
				lastXhr = $.post( "anbindungen.php",{"term":term}, function( data, status, xhr ) {
					cache[ term ] = data;
					if ( xhr === lastXhr ) {
						response( data );
					
					}
				}, "json");
			},*/
			source: function( request, response ) {
        lastXhr = $.post( "anbindungen.php",{"term":request.term}, function( data, status, xhr ) {
					cache[ request.term ] = data;
					if ( xhr === lastXhr ) {
						response( data );
					
					}
				}, "json");
    },
			open: function() {
        $( this ).addClass( "ui-active" );
      },
			select: function( event, ui ) {
				$(".ui-active").val( ui.item.cName ); 
				return false;
			},
			close: function (event, ui) { 
       $( this ).removeClass( "ui-active" );
   },
		
		})
	});
	
	});
	
});

$(document).on("focus",".machine",function(){
	var cache = {},
			lastXhr;
 		$(this).kdcomplete({
			minLength: 2,
			source: function( request, response ) {
        lastXhr = $.post( "anbindungen.php",{"term":request.term}, function( data, status, xhr ) {
					cache[ request.term ] = data;
					if ( xhr === lastXhr ) {
						response( data );
					
					}
				}, "json");
    },
			open: function() {
        $( this ).addClass( "ui-active" );
      },
			select: function( event, ui ) {
				$(".ui-active").val( ui.item.cName ); 
				return false;
			},
			close: function (event, ui) { 
       $( this ).removeClass( "ui-active" );
   },
		
		})
	});
	//setupValidate();