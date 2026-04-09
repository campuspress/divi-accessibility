;( function() {
	const opts = ( window || {} )._da11y || {};
	const debugQueue = ( window || {} )._da11yDebugQueue || [];

	const logDebugQueue = () => {
		if ( ! debugQueue.length ) {
			return;
		}

		console.groupCollapsed(
			`Divi Accessibility Debug Changes (${ debugQueue.length })`
		);
		console.log( debugQueue );
		console.groupEnd();
	};

	const out = () => {
		console.log(
			`\n%cDivi Accessibility Version ${ opts.version }`,
			'color: #FFF; background: #974DF3; padding: 3px; font-size: 15px;'
		);
		Object.keys( opts.options || {} ).forEach( opt => {
			const option = 'outline_color' === opt
				? `%c${ opts.options[ opt ] }`
				: opts.options[ opt ];
			const meta = 'outline_color' === opt
				? `color: ${ opts.options[opt] };`
				: '';
			console.log( `${ option } ← ${ opt }`, meta );
		} );
		logDebugQueue();
		console.log( "😎\n\n" );
	};
	if ( Object.keys( opts.options || {} ).length ) {
		setTimeout( out );
	}
} )();
