# DEVELOPMENT-specific deployment configuration
# please put general deployment config in config/deploy.rb

# Here you can set the server which you would like to, each server
# each role can have multiple servers, each server defined as user@server.com:port
# => port can be omiped and it defaults to 22
role :web, 'root@example.com:22'
role :app, 'root@example.com:22'
role :db, 'root@example.com:22', primary: true

# Ownership
# Uncomment if necessary...
# set :app_owner, 'www-data'
# set :app_group, 'www-data'

# Permissions
# Default: true
# set :group_writable, true

# The project's branch to use
set :branch, 'master'

# Use sudo ?
set :use_sudo, false

# Define deployments options
set :deploy_to,   -> { "/home/vhosts/#{fetch :stage}/#{fetch :application}" }
set :logs_path,   -> { "#{fetch :deploy_to}/logs" }
set :public_path, -> { "#{fetch :current_path}/public" }
set :backup_path, -> { "#{fetch :deploy_to}/backups" }

# How should we deploy?
# Valid options:
# => checkout: this deployment strategy does an SCM checkout on each target
#              host. This is the default deployment strategy for Capistrano.
#
# => copy: this deployment strategy work by preparing the source code locally,
#          compressing it, copying the file to each target host, and
#          uncompressing it to the deployment directory.
#          NOTE: This strategy has more options you can configure, please refer
#                to capistrano/recipes/deploy/strategy/copy.rb (in capistrano)
#                source or documentation for more information
#
# => export: this deployment strategy does an SCM export on each target host.
#
# => remote_cache: this deployment strategy keeps a cached checkout of the
#                  source code on each remote server. Each deploy simply updates
#                  the cached checkout, and then does a copy from the cached
#                  copy to the final deployment location.
set :deploy_via,  :remote_cache

# Keep only the last 5 releases
set :keep_releases, 5

#############
# Contents
#

# Here you can set all the contents folders, a content folder is a shared folder
# public or private but the contents are shared between all releases.
# The contents_folders is a hash of key/value where the key is the name of the folder
# created under 'shared_path/contents' and symlinked to the value (absolute path)
# you can use public_path/current_path/deploy_to etc...
set :content_folders, {
  'contents' => "#{fetch :public_path}/tl_files/contents",
}

# Here you can define which files/folder you would like to keep, these files
# and folders are not considered contents so they will not be synced from one
# server to another with the tasks mulltistage:sync:* instead they will be kept
# between versions in the shared/items folder
set :shared_items, [
  'public/system/config/localconfig.php',
  'public/.htaccess',
  'public/sitemap.xml',
]

#
#
#############

#############
# Maintenance
#

# Set the maintenance path to wherever you have stored the maintenance page,
# it could be a single file or an entire folder. The template will be parsed
# with ERB.
# if it's a folder, capistrano expects an index.html file. You could provide an
# index.rhtml file and it would be parsed with ERB before uploading to the server
# set :maintenance_path,
#   File.expand_path(File.join(File.dirname(__FILE__), '..', '..', 'maintenance'))

#
#
#############

#############
# Databases
#

# What is the type of your database server ?
# Available options: mysql
set :db_server_app, :mysql

# What is the database name for this project/stage ?
set :db_database_name, -> { "#{fetch :application}_#{fetch :stage}" }

# What is the database user ?
set :db_username, -> { "#{fetch :application}" }

# Tables to skip on import
# set :skip_tables_on_import, [
#   'tl_formdata',
#   'tl_formdata_details',
# ]

# Where the database credentials are stored on the server ?
set :db_credentials_file, -> { "#{deploy_to}/.#{db_server_app}_credentials"}

# Where can we find root credentials ?
# NOTE: Only required for db_create_user
set :db_root_credentials_file,             "/root/.#{db_server_app}_credentials"

#############
# Web server
#

# Which web server to use?
# valid options: :nginx
set :web_server_app, :nginx

# Server specific configurations
# Uncomment as necessary, default option are as follow
# set :nginx_init_path, '/etc/init.d/nginx'

# Absolute path to this application's web server configuration
# This gem suppose that you are already including files from the folder you're placing
# the config file in, if not the application won't be up after deployment
set :web_conf_file, -> { "/etc/nginx/#{fetch :stage}/#{fetch :application}.conf" }

# Which port does the server runs on ?
# Default: 80
# set :web_server_listen_port, 80

# What is the application url ?
# THis is used for Virtual Hosts
set :application_url, %W{#{fetch :application}.example.com www.#{fetch :application}.example.com}

# What are the names of the indexes
# Default: index.php, index.html
# set :web_server_indexes, %w(index.php index.html)

# Deny access ?
# Define here an array of files/pathes to deny access from.
# Default: .htaccess /system/logs
set :denied_access, %w(.htaccess /system/logs)

# HTTP Basic Authentifications
# Uncomment this if you would like to add HTTP Basic authentifications,
#
# Change the 'web_server_auth_file' to the absolute path of the htpasswd file
# web_server_auth_credentials is an array of user/password hashes, you can use
# gen_pass(length) in a Proc to generate a new password as shown below
#
# set :web_server_auth_file,        -> { "/etc/nginx/#{fetch :stage}/htpasswds/#{fetch :application}.crypt" }
# set :web_server_auth_credentials, [
#                                     {user: 'user1', password: 'pass1'},
#                                     {user: 'user2', password: -> { gen_pass(8) } },
#                                   ]

# Enable mode rewrite ?
# Default: true
# set :web_server_mod_rewrite, true

# Server mode specific configurations

# php_fpm settings
# => On which host, php-fpm is running ?
#    Default: localhost
# set :php_fpm_host, 'localhost'
# => Which port ?
#    Default: 9000
# set :php_fpm_port, '9000'

#
#
#############
