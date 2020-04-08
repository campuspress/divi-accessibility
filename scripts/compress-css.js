const sh = require( 'shelljs' );
sh.ls( 'public/partials/css/*.css' ).forEach( input => {
	if ( input.match( /\.min\.css$/ ) ) {
		return true;
	}
	const output = input.replace( /\.css$/, '.min.css' );
	sh.exec( `npx postcss ${ input } -o ${ output }` );
} );
