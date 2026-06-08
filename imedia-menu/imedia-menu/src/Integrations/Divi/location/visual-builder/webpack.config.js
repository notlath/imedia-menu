const path = require( 'path' );

module.exports = {
	entry: './src/index.jsx',
	output: {
		path: path.resolve( __dirname, '../../../../../assets/admin/divi/build' ),
		filename: 'index.js',
	},
	module: {
		rules: [
			{
				test: /\.jsx?$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: [ '@babel/preset-env', '@babel/preset-react' ],
					},
				},
			},
		],
	},
	externals: {
		react: 'React',
		'react-dom': 'ReactDOM',
		'@wordpress/api-fetch': 'wp.apiFetch',
	},
	resolve: {
		extensions: [ '.js', '.jsx' ],
	},
};
