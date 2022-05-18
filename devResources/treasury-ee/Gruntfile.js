module.exports = function(grunt) {
	// Get assetPath
	var scriptPath = './' + grunt.file.readJSON(
			'project.json'
		).source + '/coreFab/buildScripts/';

	// Set global items
	grunt.fabConfig = {};
	grunt.fabPackage = {};
	grunt.fabInitConfig = {};

	// Set up configuration
	require(scriptPath + 'configure.js')(grunt);

	// Load NPM tasks
	require(scriptPath + 'loadNpmTasks.js')(grunt);

	// Create initial clean up task
	require(scriptPath + 'initialCleanup.js')(grunt);

	// Configure clean
	require(scriptPath + 'cleanConfig.js')(grunt);

	// Configure copy
	require(scriptPath + 'copyConfig.js')(grunt);

	// Configure Less
	require(scriptPath + '/lessConfig.js')(grunt);

	// Configure javascript
	require(scriptPath + 'javascriptConfig.js')(grunt);

	// Configure jshint
	require(scriptPath + 'jshintConfig.js')(grunt);

	// Configure jscs
	require(scriptPath + 'jscsConfig.js')(grunt);

	// Configure notify
	require(scriptPath + 'notifyConfig.js')(grunt);

	// Configure browser sync
	require(scriptPath + 'browserSyncConfig.js')(grunt);

	// Configure watch
	require(scriptPath + 'watchConfig.js')(grunt);

	// Configure check
	require(scriptPath + 'check.js')(grunt);

	// Configure curl
	require(scriptPath + 'curl.js')(grunt);

	// Configure unzip
	require(scriptPath + 'unzip.js')(grunt);

	// Initialze grunt config
	grunt.initConfig(grunt.fabInitConfig);

	// Register module task
	require(scriptPath + 'createModule.js')(grunt);

	// Register updateFiles task
	require(scriptPath + 'updateFiles.js')(grunt);

	// Register tasks
	require(scriptPath + 'registerTasks.js')(grunt);
};
