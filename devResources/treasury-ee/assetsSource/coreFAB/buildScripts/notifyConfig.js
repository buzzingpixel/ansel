/*============================================================================*\
	DO NOT EDIT THIS FILE. THIS IS A CORE FILE.
/*============================================================================*/

module.exports = function(grunt) {
	// Set grunt config for notify
	grunt.fabInitConfig.notify = {
		less: {
			options: {
				title: 'CSS',
				message: 'CSS compiled successfully'
			}
		},
		uglify: {
			options: {
				title: 'JavaScript',
				message: 'JavaScript compiled successfully'
			}
		}
	};
};
