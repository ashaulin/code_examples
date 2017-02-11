# Extended Server Syntax
# ======================
# This can be used to drop a more detailed server definition into the
# server list. The second argument is a, or duck-types, Hash and is
# used to set extended properties on the server.

server 'jcdev.ru', user: 'dkosenok', roles: %w{app web db shared_db backend amqp}, :cron => true, :webinar_path => '/srv/www/jcdev.ru/wbr'

# Custom Options
# ==================

set :confirm_migrations,    false
set :notify_jenkins,        false
set :deploy_to,             "/home/justclick/justclick"
set :symfony_env,           'staging'
set :composer_install_flags,'--no-interaction --optimize-autoloader'
set :controllers_to_clear,  []
set :assets_env,            'dev'
