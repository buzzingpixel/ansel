/*============================================================================*\
	DO NOT EDIT THIS FILE. THIS IS A CORE FILE.
/*============================================================================*/

module.exports = function(grunt) {
	// Set grunt config for notify
	grunt.fabInitConfig.copy = {
		cssLib: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/css/lib/',
				src: '**',
				dest: grunt.fabConfig.assets + '/css/lib/'
			}]
		},
		jsLib: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/js/lib/',
				src: '**',
				dest: grunt.fabConfig.assets + '/js/lib/'
			}]
		},
		images: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/img/',
				src: '**',
				dest: grunt.fabConfig.assets + '/img/'
			}]
		},
		fonts: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/fonts/',
				src: '**',
				dest: grunt.fabConfig.assets + '/fonts/'
			}]
		},
		moduleBuildCssLib: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/modules/build/',
				src: '**/css/lib/**',
				dest: grunt.fabConfig.assets + '/modules/'
			}]
		},
		moduleBuildJsLib: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/modules/build/',
				src: '**/js/lib/**',
				dest: grunt.fabConfig.assets + '/modules/'
			}]
		},
		moduleBuildImages: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/modules/build/',
				src: '**/img/**',
				dest: grunt.fabConfig.assets + '/modules/'
			}]
		},
		moduleBuildFonts: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/modules/build/',
				src: '**/fonts/**',
				dest: grunt.fabConfig.assets + '/modules/'
			}]
		},
		moduleBuildFiles: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/modules/build/',
				src: '**/files/**',
				dest: grunt.fabConfig.assets + '/modules/'
			}]
		},
		moduleCompileCssLib: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/modules/compile/',
				src: '**/css/lib/**',
				dest: grunt.fabConfig.assets + '/modules/'
			}]
		},
		moduleCompileJsLib: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/modules/compile/',
				src: '**/js/lib/**',
				dest: grunt.fabConfig.assets + '/modules/'
			}]
		},
		moduleCompileImages: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/modules/compile/',
				src: '**/img/**',
				dest: grunt.fabConfig.assets + '/modules/'
			}]
		},
		moduleCompileFonts: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/modules/compile/',
				src: '**/fonts/**',
				dest: grunt.fabConfig.assets + '/modules/'
			}]
		},
		moduleCompileFiles: {
			files: [{
				expand: true,
				cwd: grunt.fabConfig.source + '/modules/compile/',
				src: '**/files/**',
				dest: grunt.fabConfig.assets + '/modules/'
			}]
		}
	};
};
