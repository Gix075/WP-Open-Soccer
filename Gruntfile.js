module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig({
            pkg: grunt.file.readJSON('package.json'),
            compress: {
                main: {
                    options: {
                        archive: 'dist/wp-open-soccer.zip'
                    },
                    files: [
                        {expand: true, cwd: 'src/', src: ['**'], dest: 'wp-open-soccer/'}
                    ]
                    }
                }
            });

        // Load the plugin that provides the "uglify" task.
        grunt.loadNpmTasks('grunt-contrib-copy'); 
        grunt.loadNpmTasks('grunt-contrib-compress');

        // Default task(s).
        grunt.registerTask('default', ['compress']);

}