# If you installed bootstrap-sass in the previous 
# step and are using Twitter's Bootstrap
require 'bootstrap-sass';

# Set this to the root of your project when deployed:
css_dir = "stylesheets/css"
images_dir = "images"
generated_images_dir =  "images"
#javascripts_dir = "public/js"

sass_dir = "stylesheets/src/scss"
#add_import_path "another/path/to/sCSS/files"
#add_import_path "yet/another/path/to/sCSS/files"

output_style = :expand
line_comments = true

sass_options = { :sourcemap => true }