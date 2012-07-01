require File.expand_path('../application', __FILE__)

######################
## PROJECT SETTINGS ##
######################

set :application,           Rails.application.config.application_name
set :scm,                   :git
set :git_enable_submodules, true

# What is the repository ?
# Default: Whatever the origin is set to
# set :project_repository,    'set your repository location here'

# Stages
set :stages, ['development', 'staging', 'production']
set :default_stage, :development

default_run_options[:pty] = true
ssh_options[:forward_agent] = true

##################
## DEPENDENCIES ##
##################

after 'deploy', 'deploy:cleanup' # keeps only last 5 releases

##################
## REQUIREMENTS ##
##################

require 'capistrano/ext/contao'
