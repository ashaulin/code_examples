# Extended Server Syntax
# ======================
# This can be used to drop a more detailed server definition into the
# server list. The second argument is a, or duck-types, Hash and is
# used to set extended properties on the server.

server 'jcstage.co', user: 'dkosenok', roles: %w{app web db shared_db}, no_logger: true

# Custom Options
# ==================

set :autostart_mysql,       true
set :deploy_to,             "/home/justclick/justclick_test0"
set :confirm_migrations,    false
set :composer_install_flags,'--no-interaction --optimize-autoloader'
set :symfony_env,           'prod'
set :controllers_to_clear,  []


Rake::Task['amqp:restart_legacy'].clear_actions()
