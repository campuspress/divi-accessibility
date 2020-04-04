const sh = require( 'shelljs' );
const path = require( 'path' );
const AdmZip = require( 'adm-zip' );
const pkg = require( '../package.json' );

const whitelist = [
	'admin',
	'divi-accessibility.php',
	'includes',
	'license.txt',
	'public',
	'readme.txt',
	'uninstall.php',
];
const package_file = `${ pkg.name }-${ pkg.version }.zip`;

console.log( `--- Packing ${ pkg.name } v${ pkg.version } ---` );
if ( sh.test( '-e', package_file ) ) {
	console.log( `\t- Removing previously existing archive: ${ package_file }` );
	sh.rm( package_file );
}


console.log( `\t- Packing:` );
const zip = new AdmZip();
sh.ls( '.' ).forEach( raw => {
	if ( whitelist.indexOf( raw ) < 0 ) {
		return true;
	}
	const entry = path.resolve( raw );
	console.log( `\t\tAdding ${ raw }` );
	if ( sh.test( '-d', entry ) ) {
		zip.addLocalFolder( entry, `${ pkg.name }/${ raw }` );
	} else {
		zip.addLocalFile( entry, pkg.name );

	}
} );
console.log( `\t- Writing package archive: ${ package_file }` );
zip.writeZip( package_file );
console.log( `--- All done ---` );
