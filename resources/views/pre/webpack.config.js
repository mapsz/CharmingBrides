const path = require('path');

module.exports = {
	mode: 'development',
	module: {
		rules: [{
			test: /\.css$/,
			use: ['style-loader', 'css-loader']
		}]
	},
	entry: './webpack/index.js',
	output: {
		path: path.resolve(__dirname, 'src'),
		filename: 'scripts.js'
	}
}