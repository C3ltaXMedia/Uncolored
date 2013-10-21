/*
 * uncolored-specific scripts
 */
jQuery( function( $ ) {
	var $pCactions = $( '#personen' );
	$pCactions.find( 'h5 a' )
		// For accessibility, show the menu when the hidden link in the menu is clicked (bug 24298)
		.click( function( e ) {
			$pCactions.find( '.personal' ).toggleClass( 'menuForceShow' );
			e.preventDefault();
		})
		// When the hidden link has focus, also set a class that will change the arrow icon
		.focus( function() {
			$pCactions.addClass( 'personalMenuFocus' );
		})
		.blur( function() {
			$pCactions.removeClass( 'personalMenuFocus' );
		});
});

$("input[placeholder]").each(function(){
  if($(this).val()==""){
    $(this).val($(this).attr("placeholder"));
    $(this).focus(function(){
      if($(this).val()==$(this).attr("placeholder")) $(this).val("");
    });
    $(this).blur(function(){
      if($(this).val()==""){
         $(this).val($(this).attr("placeholder"));
      }
     });
  }
});

/* Nutzen von sysoponly (bureaucratonly ist hier nicht in Verwendung) */
 
if ( wgUserGroups ) {
  for ( var g = 0; g < wgUserGroups.length; ++g ) {
    if ( wgUserGroups[g] == "sysop" ) {
      importStylesheet("MediaWiki:Sysop.css");
      addOnloadHook( function() {
        if ( !window.disableSysopJS ) {
          importScript("MediaWiki:Sysop.js");
        }
      } );
    } 
    else if ( wgUserGroups[g] == "bureaucrat" ) {
      importStylesheet("MediaWiki:Bureaucrat.css");
    }
  }
}