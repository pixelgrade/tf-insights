You may use the demo-theme as a kickstart or if you wish to start with the
barebones you can use the empty-theme as a starting point.

The themes are self contained projects of their own and various components of
a theme (styles, scripts, dart files, etc) use various package management
systems. To illustrate their use we've included a dependency in each:
fontawesome for styles, jquery for scripts and vector_math for dart.

It is not necessary to use the package management systems.

If you want to use something else (other then scss/css/js/dart) you can simply
create the appropriate drivers and loaders in your project. Style, Script and
Dart are merely the default ones.

Make sure your theme is mentioned in your `environment.php` or it won't be
detected.
