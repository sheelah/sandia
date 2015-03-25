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
                files: ['assets/styles/**/*.{scss,sass}'],
                tasks: ['sass:dev', 'autoprefixer']
            },
            js: {
                files: '<%= jshint.all %>',
                tasks: ['jshint', 'uglify']
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
                    'style.css': 'assets/styles/style.scss',
                    'editor-style.css': 'assets/styles/editor-style.scss'
                }
            },
            dev: {
                options: {
                    outputStyle: 'expanded',
                },
                files: {
                    'style.css': 'assets/styles/style.scss',
                    'editor-style.css': 'assets/styles/editor-style.scss'
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
            all: ['Gruntfile.js', 'assets/js/**/*.js'],
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
                dest: 'js/sandia.min.js'
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
                    sourceMap: 'assets/js/main.js.map',
                    sourceMappingURL: 'main.js.map',
                    sourceMapPrefix: 2
                },
                src: 'assets/js/sandia.min.js',
                dest: 'assets/js/sandia.min.js'
            }
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
    });

  // register task
  grunt.registerTask('default', ['dev']);
  grunt.registerTask('dev', ['concat', 'browserSync', 'watch']);
  grunt.registerTask('build', ['concat', 'uglify', 'imagemin', 'sass:dist', 'autoprefixer']);
  grunt.registerTask('lint', ['jshint']);
};
