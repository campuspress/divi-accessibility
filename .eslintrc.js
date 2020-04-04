module.exports = {
    "env": {
        "browser": true,
        "es6": true,
		"jquery": true
    },
    "extends": "eslint:recommended",
    "globals": {
        "Atomics": "readonly",
        "SharedArrayBuffer": "readonly"
    },
	"ignorePatterns": [ "**/*.min.js" ],
    "parserOptions": {
        "ecmaVersion": 2018
    },
    "rules": {
		"no-extra-semi": 0
    }
};
