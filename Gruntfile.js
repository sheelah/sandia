'use strict';
module.exports = function(grunt) {

	// Load JSON config
	var appConfig = grunt.file.readJSON('app_config.json');

	// load all grunt tasks matching the `grunt-*` pattern
	require('load-grunt-tasks')(grunt);

	// Show elapsed time
	require('time-grunt')(grunt);

	grunt.initConfig({

		// watch for changes and trigger sass, jshint, uglify and livereload
		watch: {
			sass: {
				files: ['assets/sass/**/*.{scss,sass}'],
				tasks: ['sass:dev', 'autoprefixer']
			},
			js: {
				files: '<%= jshint.all %>',
				tasks: ['concat', 'copy', 'jshint'],
				options: {
					spawn: false,
				}
			},
			images: {
				files: ['assets/images/**/*.{png,jpg,gif}'],
				tasks: ['imagemin']
			},
			options: {
				livereload: true
			}
		},

		// sass
		sass: {
			options: {
				sourceMap: true,
				sourceMapContents: true
			},
			dist: {
				options: {
					outputStyle: 'compressed',
				},
				files: {
					'style.css': 'assets/sass/style.scss',
					'editor-style.css': 'assets/sass/editor-style.scss'
				}
			},
			dev: {
				options: {
					outputStyle: 'expanded',
				},
				files: {
					'style.css': 'assets/sass/style.scss',
					'editor-style.css': 'assets/sass/editor-style.scss'
				}
			}
		},

		// autoprefixer
		autoprefixer: {
			options: {
				browsers: ['last 2 versions', 'ie 9', 'ios 6', 'android 4'],
				map: true
			},
			files: {
				expand: true,
				cwd: '.',
				src: ['style.css', 'editor-style.css'],
				dest: '.'
			},
		},

		// javascript linting with jshint
		jshint: {
			options: {
				reporter: require('jshint-stylish')
			},
			js: {
				options: {
					curly: true,
					eqeqeq: true,
					immed: true,
					latedef: true,
					newcap: true,
					noarg: true,
					sub: true,
					undef: true,
					unused: true,
					boss: true,
					eqnull: true,
					browser: true,
					globals: {
						jQuery: true,
						$: true,
					}
				}
			},
			all: ['Gruntfile.js', 'assets/js/src/*.js'],
		},

		// concatenate js files
		concat: {
			options: {
			stripBanners: true,
			nonull: true,
		},
			main: {
				src: ['assets/js/src/*.js',
					  '!assets/js/src/customizer.js',
					  'assets/js/lib/*.js'
					 ],
				dest: 'assets/js/sandia.min.js'
			}
		},


		// uglify to concat, minify, and make lib maps
		uglify: {
			customizer: {
				src: 'assets/js/src/customizer.js',
				dest: 'assets/js/customizer.min.js',
			},
			main: {
				options: {
					sourceMap: 'assets/js/sandia.js.map',
					sourceMappingURL: 'sandia.js.map',
					sourceMapPrefix: 2
				},
				src: 'assets/js/sandia.min.js',
				dest: 'assets/js/sandia.min.js'
			}
		},
		copy: {
			dev: {
				files: [
					{
						src: ['assets/js/src/customizer.js'],
						dest: 'assets/js/customizer.min.js'
					},
				]
			},
		},

		// image optimization
		imagemin: {
			dist: {
				options: {
					optimizationLevel: 7,
					progressive: true,
					interlaced: true
				},
				files: [{
					expand: true,
					cwd: 'assets/images/',
					src: ['**/*.{png,jpg,gif}'],
					dest: 'assets/images/'
				}]
			}
		},

		// browserSync
		browserSync: {
			dev: {
				bsFiles: {
					src : [
						'style.css',
						'assets/js/**/*.js',
						'assets/images/**/*.{png,jpg,jpeg,gif,webp,svg}',
						'**/*.php'
					],
				},
				options: {
					proxy: appConfig['proxy'],
					watchTask: true,
					debugInfo: true,
					logConnections: true,
					notify: true
				}
			}
		},
		addtextdomain: {
			options: {
				textdomain: 'sandia'
			},
			target: {
				files: {
					src: [
							'*.php',
							'**/*.php',
							'!node_modules/**',
							'!php-tests/**'
					]
				}
			}
		},
		makepot: {
			target: {
				options: {
					cwd: '',                          // Directory of files to internationalize.
					domainPath: '/languages',         // Where to save the POT file.
					exclude: [],                      // List of files or directories to ignore.
					include: [],                      // List of files or directories to include.
					mainFile: '',                     // Main project file.
					potComments: '',                  // The copyright at the beginning of the POT file.
					potFilename: 'sandia.pot',        // Name of the POT file.
					potHeaders: {
						poedit: true,                 // Includes common Poedit headers.
						'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
					},                                // Headers to add to the generated POT file.
					processPot: null,                 // A callback function for manipulating the POT file.
					type: 'wp-theme',                 // Type of project (wp-plugin or wp-theme).
					updateTimestamp: true,            // Whether the POT-Creation-Date should be updated without other changes.
					updatePoFiles: false              // Whether to update PO files in the same directory as the POT file.
				}
			}
		}
	});

	// register task
	grunt.registerTask('default', ['dev']);
	grunt.registerTask('dev', ['copy', 'concat', 'browserSync', 'watch']);
	grunt.registerTask('build', ['concat', 'uglify', 'imagemin', 'sass:dist', 'autoprefixer']);
	grunt.registerTask('lint', ['jshint']);
	grunt.registerTask('i18n', ['addtextdomain', 'makepot']);

};
