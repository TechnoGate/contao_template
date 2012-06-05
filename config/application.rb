require File.expand_path('../boot', __FILE__)

# Initialize the application
Dir["#{TechnoGate::Contao.root.join('config', 'initializers')}/**/*.rb"].each {|f| require f}

TechnoGate::Contao::Application.configure do
  config.application_name   = 'contao_template'
  config.javascripts_path   = ["vendor/assets/javascripts", "lib/assets/javascripts", "app/assets/javascripts"]
  config.stylesheets_path   = 'app/assets/stylesheets'
  config.images_path        = 'app/assets/images'
  config.contao_path        = 'contao'
  config.contao_public_path = 'public'
  config.assets_public_path = 'public/resources'
end
