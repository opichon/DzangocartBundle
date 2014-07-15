var gulp = require("gulp");
var bowerFiles = require("gulp-bower-files");
var jsmin = require("gulp-jsmin");
var rename = require("gulp-rename");

gulp.task("bower-files", function() {
	 bowerFiles()
		.pipe(gulp.dest("../public/vendor"));
});

gulp.task("minify", function() {
	gulp.src(["../public/vendor/**/*.js", "!../public/vendor/**/*min.js"])
		.pipe(jsmin())
		.pipe(rename({suffix: ".min"}))
		.pipe(gulp.dest("../public/vendor"));
});