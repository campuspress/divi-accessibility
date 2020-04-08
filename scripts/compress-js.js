const sh = require( 'shelljs' );
sh.ls( 'public/partials/js/*.js' ).forEach( input => {
	if ( input.match( /\.min\.js$/ ) ) {
		return true;
	}
	const output = input.replace( /\.js$/, '.min.js' );
	sh.exec( `npx terser ${ input } -c -m -o ${ output }` );
} );
