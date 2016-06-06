module.exports = function(grunt) {
    grunt.initConfig({
        'string-replace': {
            inline: {
                files: {
                    '../apps/web/views/_elements/js-lib.volt': '../apps/web/views/_elements/js-lib.volt'
                },
                options: {
                    replacements: [
                        {
                            pattern: '<!--start PROD imports',
                            replacement: '<!--start PROD imports-->'
                        },
                        {
                            pattern: 'end PROD imports-->',
                            replacement: '<!--end PROD imports-->'
                        },
                        {
                            pattern: '<!--start DEV imports-->',
                            replacement: '<!--start DEV imports'
                        },
                        {
                            pattern: '<!--end DEV imports-->',
                            replacement: 'end DEV imports-->'
                        }
                    ]
                }
            }
        },
        uglify: {
            minify: {
                files: {
                    '../public/w/js/main.min.js': '../public/w/js/main.js'
                }
            }
        },
    });
    grunt.loadNpmTasks('grunt-string-replace');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.registerTask('default', ['uglify','string-replace']);
};