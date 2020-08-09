$( function () {
	var darkMode = $( document.documentElement ).hasClass( 'client-dark-mode' );
	var api = new mw.Api();

	$( '#pt-darkmode-link a' ).on( 'click', function ( e ) {
		e.preventDefault();
		darkMode = !darkMode;

		$( document.documentElement ).toggleClass( 'client-dark-mode', darkMode );
		$( e.target ).text( mw.msg( darkMode ? 'darkmode-default-link' : 'darkmode-link' ) );

		api.saveOption(
			'darkmode-enabled',
			darkMode ? 1 : 0,
		);
	} );
} );
