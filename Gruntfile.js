/*global module:false*/
module.exports = function(grunt) {

	// load all grunt tasks
	require( 'load-grunt-tasks' )( grunt );

	/**
	* load configuration from any number of JSON files
	* merging them in order specified
	*/
	grunt.mergeConfig = function(paths) {
		var config = {};
		for (var i in paths) {
			var path = paths[i];
			if (grunt.file.exists(path)) {
				config = grunt.file.readJSON( path );
				grunt.config.merge ( {config:config} );
				grunt.log.subhead('merged in ' + path);
			}
		}
	};
	
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
				dest: '<%= config.dist %>/',
				cwd: '<%= config.src %>/',
				src: [ '*.php', '*.png', 'fonts/*.*', 'js/*.*', 'images/*.*' ]
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

		webfont: {
			icons: {
				src: '<%= config.src %>/icons/*.svg',
				dest: '<%= config.dist %>/fonts/',
				destCss: '<%= config.src %>/styles/',
				options: {
					engine: 'node',
					font: 'icons',
					stylesheet: 'less',
					relativeFontPath: './fonts/',
					destHtml: '<%= config.src %>/icons/'
				}
			}
		},

		rename: {
			development: {

				files: [

					{ src: [ '<%= config.src %>/styles/icons.less' ], dest: [ '<%= config.src %>/styles/_icons.less' ] },
				]
			}
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

	grunt.registerTask( 'prepare:icons', [

		'webfont:icons',
		'rename:development'
	]);

	grunt.registerTask( 'prepare:development', [

		'clean:development',
		'copy:development',
		'less:development',
		'prepare:icons',
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
	
	// optional overrides from local config file
	grunt.mergeConfig(['config.local.json']);
		
};
