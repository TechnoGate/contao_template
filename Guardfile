require File.expand_path('../config/application', __FILE__)

guard 'assets' do
  # Applications
  watch(%r{^(app/assets/javascripts/.+\.js)$})
  watch(%r{^(app/assets/javascripts/.+\.coffee)$})
  watch(%r{^(app/assets/stylesheets/(.*)\.s[ac]ss)$})

  # In house libraries
  watch(%r{^(lib/assets/javascripts/.+\.js)$})
  watch(%r{^(lib/assets/javascripts/.+\.coffee)$})
  watch(%r{^(lib/assets/stylesheets/(.*)\.s[ac]ss)$})

  # Vendor files
  watch(%r{^(vendor/assets/javascripts/.+\.js)$})
  watch(%r{^(vendor/assets/javascripts/.+\.coffee)$})
  watch(%r{^(vendor/assets/stylesheets/(.*)\.s[ac]ss)$})
end

options = {
  compilers: [:coffeescript],
  input: {
    coffeescript: ['spec/javascripts']
  },
  output: {
    coffeescript: 'spec/javascripts/compiled'
  },
}

guard 'assets', options do
  watch(%r{^(spec/javascripts/.+_[Ss]pec.*\.coffee)$})
end
