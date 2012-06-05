require 'rubygems'

# Set up gems listed in the Gemfile.
ENV['BUNDLE_GEMFILE'] ||= File.expand_path('../../Gemfile', __FILE__)

require 'bundler/setup' if File.exists?(ENV['BUNDLE_GEMFILE'])
require 'contao'

ENV['CONTAO_ENV'] ||= 'development'
TechnoGate::Contao.env = ENV['CONTAO_ENV'].to_sym
TechnoGate::Contao.root = File.dirname(ENV['BUNDLE_GEMFILE'])
