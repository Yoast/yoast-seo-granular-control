// See https://github.com/gruntjs/grunt-contrib-copy
module.exports = {
	artifact: {
		files: [
			{
				expand: true,
				cwd: ".",
				src: [
					"admin/**",
					"css/**/*.css",
					"frontend/**",
					"includes/**",
					"js/*.min.js",
					"languages/**",
					"vendor/**",
					"index.php",
					"readme.txt",
					"yoast-seo-granular-control.php",
					"!vendor/bin/**",
					"!vendor/composer/installed.json",
					"!vendor/composer/installers/**",
					"!**/composer.json",
					"!**/README.md",
				],
				dest: "<%= files.artifact %>",
			},
		],
	},
	"composer-artifact": {
		files: [ {
			expand: true,
			cwd: "<%= files.artifact %>",
			src: [
				"**/*",
				"!vendor_prefixed/**",
			],
			dest: "<%= files.artifactComposer %>",
		} ],
	},
	"composer-files": {
		files: [ {
			expand: true,
			cwd: ".",
			src: [
				"composer.lock",
				"composer.json",
			],
			dest: "<%= files.artifactComposer %>",
		} ],
		"composer.lock": [ "<%= files.artifact %>/composer.lock" ],
		"composer.json": [ "<%= files.artifact %>/composer.json" ],
	},
};
