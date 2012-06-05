# Require any additional compass plugins here.
require 'compass-h5bp'

# Assets path
css_dir = 'public/resources'
sass_dir = 'app/assets/stylesheets'
images_dir = 'app/assets/images'
javascripts_dir = 'app/assets/javascripts'
generated_images_dir = 'public/resources'

# HTTP Path
http_path = '/resources'
http_images_path = '/resources'
http_javascripts_path = '/resources'
http_generated_images_path = '/resources'

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed
output_style = (ENV['CONTAO_ENV'] == 'production') ? :compressed : :nested

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = false

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = (ENV['CONTAO_ENV'] == 'production') ? false : true
