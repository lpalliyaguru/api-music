module.exports = function(grunt){

	grunt.initConfig({
		pkg : grunt.file.readJSON('package.json'),
		bowercopy: {
			options: {
				srcPrefix : 'bower_components',
				destPrefix : 'web/assets'
			},
			scripts: {
				files: {
					'js/jquery.js' : 'jquery/dist/jquery.js',
					'js/bootstrap.js' : 'bootstrap/dist/js/bootstrap.js'
				}
			},
			stylesheets : {
				files: {
					'css/bootstrap.css' : 'bootstrap/dist/css/bootstrap.css',
					'css/font-awesome.css' : 'font-awesome/css/font-awesome.css'
				}
				
			},
			fonts: {
				files: {
					'fonts' : 'font-awesome/fonts'
				}			
			
			}
			
		},
		cssmin: {
			bootstrap : {
				src : 'web/assets/css/bootstrap.css',
				dest : 'web/assets/css/bootstrap.min.css'
			},
			"font-awesome": {
				src: 'web/assets/css/font-awesome.css',
				dest: 'web/assets/css/font-awesome.min.css'
			}
			
		},
		uglify : {
		
			js : {
				files : {
					 'web/assets/js/bundled.min.js': ['web/assets/js/bundled.js']
				}
			
			}
		},
		concat : {
			options : {
				stripBanners : true
			},
			css : {
				src : [
					'web/assets/js/jquery.js',
					'web/assets/js/bootstrap.js',
					'web/js/app.js'
				],
				dest : 'web/assets/js/bundled.js'
			}
		},
		copy : {
			images: {
				expand : true,
				cwd : 'web/images/',
				src : '*',
				dest : 'web/assets/images/'
			}
		}
	
	});
	grunt.loadNpmTasks('grunt-bowercopy');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.registerTask('default', ['bowercopy','copy', 'concat', 'cssmin', 'uglify']);

}
