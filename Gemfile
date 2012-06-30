source 'https://rubygems.org'

gem 'rails', '3.2.6'

# Bundle edge Rails instead:
# gem 'rails', :git => 'git://github.com/rails/rails.git'

# Gems used only for assets and not required
# in production environments by default.
group :assets do
  gem 'sass-rails',   '~> 3.2.3'
  gem 'coffee-rails', '~> 3.2.1'

  # See https://github.com/sstephenson/execjs#readme for more supported runtimes
  gem 'therubyracer', :platforms => :ruby

  gem 'uglifier', '>= 1.0.3'
  gem 'oily_png', platforms: :mri
  gem 'compass-rails'
end

gem 'jquery-rails'

# Deploy with Capistrano
# gem 'capistrano'

group :development do
  gem 'foreman'
  gem 'contao', github: 'TechnoGate/contao', branch: 'use_rails'
end

group :development, :test do
  gem 'jasmine-rails'
  gem 'jasmine-headless-webkit', '>= 0.9.0.rc2'
  gem 'jasmine-spec-extras'
end
