/*global module:false*/
module.exports = function(grunt) {

	// load all grunt tasks
	require( 'load-grunt-tasks' )( grunt );

	// Project configuration.
	grunt.initConfig({

		globals: {
			src: './src/wp-content/themes/twentyfifteen-child',
			dist: './dist/wp-content/themes/twentyfifteen-child'
		},

		clean: {
			development: {
				src: [ 
					'<%= globals.dist %>/*.php',
					'<%= globals.dist %>/*.png',
					'<%= globals.dist %>/fonts/*.*',
					'<%= globals.dist %>/js/scripts.js',
				]
			},
		},

		copy: {
			development: {
				expand: true,
				dest: '<%= globals.dist %>',
				cwd: '<%= globals.src %>',
				src: [ '*.php', '*.png', 'fonts/*.*', 'js/*.*' ]
			},
		},

		less: {
			development: {
				files: {
					'<%= globals.dist %>/style.css': '<%= globals.src %>/styles/style.less'
				}
			},
			production: {
				options: {
					cleancss: true,
				},
				files: {
					'<%= globals.dist %>/style.css': '<%= globals.src %>/styles/style.less'
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
				src: '<%= globals.dist %>/style.css'
			},
			production: {
				src: '<%= globals.dist %>/style.css'
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
				dest: '<%= globals.dist %>/js/libs.js',
				dependencies: {}
			}
		},

		watch: {
		    development: {
		    	files: [
		    		'Gruntfile.js',
					'<%= globals.src %>/**/*.*',
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
				path: 'http://local.sandvikcoromant.styleguideplatform.com/'
			}
		},

		'ftp-deploy': {
			acceptance: {
				auth: {
					host: 'theidentitymanual.com',
					port: 21,
					authKey: 'key',
				},
				src: '<%= globals.dist %>',
				dest: './wp-content/themes/twentyfifteen-child/',
				exclusions: [ '<%= globals.dist %>/**/.DS_Store' ]
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
		'ftp-deploy:acceptance',
	]);
};
