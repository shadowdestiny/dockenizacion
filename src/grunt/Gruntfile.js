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
                    '../public/w/js/dist/main.min.js': '../public/w/js/main.js',
                    '../public/w/js/dist/mobileInit.min.js': '../public/w/js/mobileInit.js',
                    '../public/w/js/dist/btnMsgHide.min.js': '../public/w/js/btnMsgHide.js',
                    '../public/w/js/dist/CheckWin.min.js': '../public/w/js/CheckWin.js',
                    '../public/w/js/dist/GASignUpAttempt.min.js': '../public/w/js/GASignUpAttempt.js',
                    '../public/w/js/dist/GASignUpLanding.min.js': '../public/w/js/GASignUpLanding.js',
                    '../public/w/js/dist/GASignUpOrder.min.js': '../public/w/js/GASignUpOrder.js',
                }
            }
        },
    });
    grunt.loadNpmTasks('grunt-string-replace');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.registerTask('default', ['uglify','string-replace']);
};