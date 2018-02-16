module.exports = function(grunt) {
// Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {
        banner: '/*! ontraq <%= grunt.template.today("yyyy-mm-dd") %> */\n',
        beautify: true,
        mangle: false
      },
      build: {
        src: [
          'javascripts/src/vendor/jquery182.js',
          'javascripts/src/vendor/bootstrap/bootstrap.js',
          'javascripts/src/vendor/fullcalendar/calendar_events.js',
          'javascripts/src/vendor/fullcalendar/fullcalendar.js',
          'javascripts/src/vendor/jquery_uniform.js',
          'javascripts/src/vendor/jquery.icheck.js',
          'javascripts/src/vendor/jquery.dataTables.min.js',
          'javascripts/src/vendor/editable/bootstrap-editable.js',
          'javascripts/src/angular/angular.min.js',
          'javascripts/src/vendor/underscore.min.js',
          'javascripts/src/generic.js', 
          'javascripts/src/recovery/common.js',
          'javascripts/src/recovery/app.js',
          'javascripts/src/recovery/controllers/bin.js',
          'javascripts/src/recovery/controllers/process.js',
          'javascripts/src/recovery/controllers/checkin.js',
          'javascripts/src/recovery/directives/directives.js'
        ],
        dest: 'javascripts/application.min.js'
      }
    },
    compass: {
        dev: {
          options: {
            config: 'config.rb'
          }
        },
        prod: {
          options: {
            config: 'config_prod.rb'
          }
        }
    },
    watch: {
      dev: {
          files: ['stylesheets/**/*.scss'],
          tasks: ['compass:dev'],
      },
      ie: {
          files: ['stylesheets/**/*.scss'],
          tasks: ['compass:prod'],
          files: ['javascripts/test/**/*.js'],
          tasks: ['uglify'],
      },
    }
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  // Load the plugin that provides the "compass" task
  grunt.loadNpmTasks('grunt-contrib-compass');
  // Load the plugin that provides the "watch" task
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Default task(s).
  grunt.registerTask('default', ['compass:dev','watch:dev']);
  grunt.registerTask('prod', ['uglify','compass:prod']);
  grunt.registerTask('ie', ['uglify','compass:prod','watch:ie']);
};