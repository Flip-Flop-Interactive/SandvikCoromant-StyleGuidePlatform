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
		for( var i in paths ){
			var path = paths[ i ];
			if( grunt.file.exists( path )){

				config = grunt.file.readJSON( path );
				grunt.config.merge({ config: config });
				grunt.log.subhead( 'merged in ' + path );
			}
		}
	};
	
	// Project configuration.
	grunt.initConfig({

		config: grunt.file.readJSON( 'config.json' ),

		clean: {
			development: {
				src: [ 
					'<%= config.dist.theme %>/*.php',
					'<%= config.dist.theme %>/page-templates/*.php',
					'<%= config.dist.theme %>/*.png',
					'<%= config.dist.theme %>/fonts/*.*',
					'<%= config.dist.theme %>/js/scripts.js',
					'<%= config.dist.theme %>/images/*.*',
					'<%= config.dist.theme %>/videos/*.*',
				]
			},
		},

		copy: {
			theme: {
				expand: true,
				dest: '<%= config.dist.theme %>/',
				cwd: '<%= config.src.theme %>/',
				src: [ 
					'*.php',
					'page-templates/*.php', 
					'*.png', 
					'fonts/*.*', 
					'js/*.*', 
					'images/*.*', 
					'videos/*.*' ]
			},
			plugin: {
				expand: true,
				dest: '<%= config.dist.plugin %>/',
				cwd: '<%= config.src.plugin %>/',
				src: [ 'password-protected-login-skin.php' ]
			},
		},

		less: {
			development: {
				files: {
					'<%= config.dist.theme %>/style.css': '<%= config.src.theme %>/styles/style.less'
				}
			},
			production: {
				options: {
					cleancss: true,
				},
				files: {
					'<%= config.dist.theme %>/style.css': '<%= config.src.theme %>/styles/style.less'
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
				src: '<%= config.dist.theme %>/style.css'
			},
			production: {
				src: '<%= config.dist.theme %>/style.css'
			},
		},

		webfont: {
			icons: {
				src: '<%= config.src.theme %>/icons/*.svg',
				dest: '<%= config.dist.theme %>/fonts/',
				destCss: '<%= config.src.theme %>/styles/',
				options: {
					engine: 'node',
					font: 'icons',
					stylesheet: 'less',
					relativeFontPath: './fonts/',
					destHtml: '<%= config.src.theme %>/icons/'
				}
			}
		},

		rename: {
			development: {

				files: [

					{ src: [ '<%= config.src.theme %>/styles/icons.less' ], dest: [ '<%= config.src.theme %>/styles/_icons.less' ] },
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
				dest: '<%= config.dist.theme %>/js/libs.js',
				dependencies: {
					'bootstrap': 'jquery',
					'headroom': 'jquery',
					'vide': 'jquery'
				},
			}
		},

		watch: {
				development: {
					files: [
						'Gruntfile.js',
						'<%= config.src.theme %>/**/*.*',
						'<%= config.src.plugin %>/*.*',
						'!<%= config.src.theme %>/styles/_icons.less',
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
			theme: {
				auth: {
					host: 'theidentitymanual.com',
					port: 21,
					authKey: 'key',
				},
				src: '<%= config.dist.theme %>',
				dest: '<%= config.ftp_dist.theme %>',
				exclusions: [ '<%= config.dist.theme %>/**/.DS_Store' ]
			},
			plugin: {
				auth: {
					host: 'theidentitymanual.com',
					port: 21,
					authKey: 'key',
				},
				src: '<%= config.dist.plugin %>',
				dest: '<%= config.ftp_dist.plugin %>',
				exclusions: [ '<%= config.dist.plugin %>/**/.DS_Store' ]
			},			
		}
	});

	grunt.registerTask( 'prepare:icons', [

		'webfont:icons',
		'rename:development'
	]);

	grunt.registerTask( 'prepare:development', [

		'clean:development',
		'copy:theme',
		'copy:plugin',
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
		'ftp-deploy:theme',
		'ftp-deploy:plugin',
	]);
	
	// optional overrides from local config file
	grunt.mergeConfig(['config.local.json']);	
};
