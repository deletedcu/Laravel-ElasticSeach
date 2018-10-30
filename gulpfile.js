var elixir = require('laravel-elixir');
var bower = './resources/assets/vendor/';

//elixir.config.registerWatcher("default", "resources/assets/**");
elixir.config.sourcemaps = false; //for live version
// elixir.config.sourcemaps = true; //for dev version

var gulp = require('gulp'),
    watch = require('gulp-watch');

gulp.task('stream', function() {
    return gulp.src('./resources/assets/sass/*.scss')
        .pipe(watch('scss/**/*.scss'))
        .pipe(gulp.dest('build'));
});

var cmsScripts = [
    "jquery/dist/jquery.min.js",
    "jquery-ui/jquery-ui.min.js",
    "bootstrap/dist/js/bootstrap.min.js",
    "chosen/chosen.jquery.js",
    "js-modified-plugins/**",
    "tinymce/themes/modern/theme.js",
    "bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js",
    "moment/min/moment-with-locales.min.js",
    "eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js",
    // "bootstrap-treeview/public/js/bootstrap-treeview.js",
    "datatables/media/js/jquery.dataTables.js",
    "twitter-bootstrap-wizard/jquery.bootstrap.wizard.js",
    "bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js",
    "fullcalendar/dist/fullcalendar.js",
    "js/**",
    //frontend //just the folder  look at line 2:)

];
// dodat sass
elixir(function(mix) {
    mix
        .sass('app.scss', 'public/css/style.css')
        .styles(cmsScripts, 'public/js/script.js', bower)
        .version(['public/js/script.js', 'public/css/style.css'])
        .copy(bower + 'bootstrap/fonts/', 'public/build/fonts/')
        .copy(bower + 'font-awesome/fonts', 'public/build/fonts/')
        .copy(bower + 'jquery-ui/themes/base/images/', 'public/img/jquery-ui')
        .copy(bower + 'chosen/chosen-sprite.png', 'public/build/css/')
        .copy(bower + 'chosen/chosen-sprite@2x.png', 'public/build/css/')
        .copy(bower + 'tinymce/skins/lightgray/fonts/', 'public/style/css/fonts/')
        .copy(bower + 'tinymce/skins/lightgray/img/', 'public/build/css/img/')
        .copy(bower + 'tinymce/skins/lightgray/skin.min.css', 'public/css/style/')
        .copy(bower + 'tinymce/skins/lightgray/content.min.css', 'public/css/style/')
        .copy(bower + 'tinymce/skins/lightgray/fonts/', 'public/css/style/fonts/')
        .copy(bower + 'js-modified-plugins/tinymce-plugins/table/plugin.js', 'public/build/js/plugins/table/')
        .copy(bower + 'js-modified-plugins/tinymce-plugins/link/plugin.js', 'public/build/js/plugins/link/')
        .copy(bower + 'tinymce/plugins/spellchecker/', 'public/build/js/plugins/spellchecker/')
        .copy(bower + 'tinymce/plugins/anchor/', 'public/build/js/plugins/anchor/')
        .copy(bower + 'tinymce/plugins/image/', 'public/build/js/plugins/image/')
        .copy(bower + 'tinymce/plugins/code/', 'public/build/js/plugins/code/')
        .copy(bower + 'datatables/media/images/', 'public/build/images/')
        .copy(bower + 'bootstrap-colorpicker/dist/img/', 'public/build/img/')
        .copy(bower + 'img/', 'public/img/');

});
