/*global module:false*/
module.exports = function(grunt) {

	// load all grunt tasks
	require( 'load-grunt-tasks' )( grunt );

	// Project configuration.
	grunt.initConfig({

		config: grunt.file.readJSON( 'config.json' ),

		clean: {
			development: {
				src: [ 
					'<%= config.dist %>/*.php',
					'<%= config.dist %>/*.png',
					'<%= config.dist %>/fonts/*.*',
					'<%= config.dist %>/js/scripts.js',
				]
			},
		},

		copy: {
			development: {
				expand: true,
				dest: '<%= config.dist %>',
				cwd: '<%= config.src %>',
				src: [ '*.php', '*.png', 'fonts/*.*', 'js/*.*' ]
			},
		},

		less: {
			development: {
				files: {
					'<%= config.dist %>/style.css': '<%= config.src %>/styles/style.less'
				}
			},
			production: {
				options: {
					cleancss: true,
				},
				files: {
					'<%= config.dist %>/style.css': '<%= config.src %>/styles/style.less'
				}
			}
		},

		autoprefixer: {
			options: {
				browsers: [
					'Android 2.3',
					'Android >= 4',
					'Chrome >= 20',
					'Firefox >= 24', // Firefox 24 is the latest ESR
					'Explorer >= 8',
					'iOS >= 6',
					'Opera >= 12',
					'Safari >= 6'
				]
			},
			development: {
				src: '<%= config.dist %>/style.css'
			},
			production: {
				src: '<%= config.dist %>/style.css'
			},
		},

		concat: {},

		uglify: {},

		jshint: {
			options: {
				jshintrc: '.jshintrc',
				reporter: require( 'jshint-stylish' )
			},
			development: [
				'Gruntfile.js',
			]
		},

		bower_concat: {
			development: {
				dest: '<%= config.dist %>/js/libs.js',
				dependencies: {}
			}
		},

		watch: {
		    development: {
		    	files: [
		    		'Gruntfile.js',
					'<%= config.src %>/**/*.*',
		    	],
		    	tasks: [ 
		    		'prepare:development'
		    	],
	            options: {
    	            livereload: true
		        }
		    }
		},

		open: {
			development: {
				path: '<%= config.local_url %>'
			}
		},

		'ftp-deploy': {
			acceptance: {
				auth: {
					host: 'theidentitymanual.com',
					port: 21,
					authKey: 'key',
				},
				src: '<%= config.dist %>',
				dest: '<%= config.ftp_dest %>',
				exclusions: [ '<%= config.dist %>/**/.DS_Store' ]
			},
		}
	});

	grunt.registerTask( 'prepare:development', [

		'clean:development',
		'copy:development',
		'less:development',
		'autoprefixer:development',
		'jshint:development',
	]);	

	grunt.registerTask( 'development', [

		'prepare:development',
		'open:development',
		'watch:development'
	]);

	grunt.registerTask( 'acceptance', [

		'prepare:development',
		'bower_concat:development',
		'ftp-deploy:acceptance',
	]);
};
