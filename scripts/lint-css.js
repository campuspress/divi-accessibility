const fs = require('fs');
const path = require('path');
const postcss = require('postcss');
const sh = require('shelljs');

const cssFiles = sh.ls('public/partials/css/*.css').filter((input) => !input.match(/\.min\.css$/));

let hasErrors = false;

cssFiles.forEach((input) => {
	try {
		postcss().process(fs.readFileSync(input, 'utf8'), {
			from: path.resolve(input),
		});
	} catch (error) {
		hasErrors = true;
		console.error(`CSS parse error in ${input}`);
		console.error(error.message);
	}
});

if (hasErrors) {
	process.exit(1);
}
