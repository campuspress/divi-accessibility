module.exports = [
	{
		ignores: ['**/*.min.js'],
	},
	{
		files: ['public/partials/js/**/*.js'],
		languageOptions: {
			ecmaVersion: 2018,
			sourceType: 'script',
			globals: {
				$: 'readonly',
				Atomics: 'readonly',
				SharedArrayBuffer: 'readonly',
				console: 'readonly',
				document: 'readonly',
				jQuery: 'readonly',
				setTimeout: 'readonly',
				window: 'readonly',
			},
		},
		rules: {
			'no-extra-semi': 'off',
			'no-undef': 'error',
			'no-unused-vars': ['error', {
				args: 'none',
			}],
		},
	},
];
