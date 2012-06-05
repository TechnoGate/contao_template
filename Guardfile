require File.expand_path('../config/application', __FILE__)

guard 'assets' do
  # Applications
  watch(%r{^(app/assets/javascripts/.+\.js)$})
  watch(%r{^(app/assets/javascripts/.+\.js\.coffee)$})
  watch(%r{^(app/assets/stylesheets/(.*)\.s[ac]ss)$})

  # In house libraries
  watch(%r{^(lib/assets/javascripts/.+\.js)$})
  watch(%r{^(lib/assets/javascripts/.+\.js\.coffee)$})
  watch(%r{^(lib/assets/stylesheets/(.*)\.s[ac]ss)$})

  # Vendor files
  watch(%r{^(vendor/assets/javascripts/.+\.js)$})
  watch(%r{^(vendor/assets/javascripts/.+\.js\.coffee)$})
  watch(%r{^(vendor/assets/stylesheets/(.*)\.s[ac]ss)$})
end
