const compare = require( 'compare-versions' );
const fs = require( 'fs' );

const pkg = require( '../package.json' );
const mainFile = [];
const versionCandidates = {
	package: pkg.version,
};

const versionRxSrc = '\\d+\\.(?:\\d\.)+\\d(?:(?:-alpha|-beta)-\\d+)?';
const versionRx = new RegExp( versionRxSrc );
const fileHeaderRx = new RegExp( `\\* Version:\\s+(${ versionRxSrc })\\s*$` );
const defineRx = new RegExp(
	`define\\(\\s*[\'"]DA11Y_VERSION\[\'"],\\s*?[\'"](${ versionRxSrc })[\'"]\\s*\\);`
);

fs.readFileSync('divi-accessibility.php', 'utf8').split( /\n/ ).forEach( line => {
	if ( ( line || '' ).match( fileHeaderRx ) ) {
		versionCandidates.fileheader = line.match( fileHeaderRx )[1];
	}
	if ( ( line || '' ).match( defineRx ) ) {
		versionCandidates.define = line.match( defineRx )[1];
	}
	mainFile.push( line );
} );

let finalVersion;
if ( process.env.npm_config_release ) {
	finalVersion = process.env.npm_config_release;
} else {
	finalVersion = Object.values( versionCandidates ).reduce(
		( previous, ver ) => compare( `${ previous }`, `${ ver }` ) >= 0 ? previous : ver,
		'0.0.0'
	);
}

if ( compare( '' + versionCandidates.package, '' + finalVersion ) !== 0 ) {
	console.log( `\t- Updating package.json version` );
	pkg.version = finalVersion;
	fs.writeFileSync( 'package.json', JSON.stringify( pkg, null, 2 ) );
}

if (
	compare( '' + versionCandidates.fileheader, '' + finalVersion ) !== 0 ||
	compare( '' + versionCandidates.define, '' + finalVersion ) !== 0
) {
	console.log( `\t- Updating main file versions` );
	fs.writeFileSync( 'divi-accessibility.php', mainFile.map( line => {
		if ( ( line || '' ).match( fileHeaderRx ) ) {
			return line.replace( versionRx, finalVersion );
		}
		if ( ( line || '' ).match( defineRx ) ) {
			return line.replace( versionRx, finalVersion );
		}

		return line;

	} ).join( "\n" ) );
}
