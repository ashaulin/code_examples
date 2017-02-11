# Extended Server Syntax
# ======================
# This can be used to drop a more detailed server definition into the
# server list. The second argument is a, or duck-types, Hash and is
# used to set extended properties on the server.

server 'vs97.justclick.ru', user: 'jenkins', roles: %w{app test_db}

# Custom Options
# ==================

set :deploy_to,             "/home/justclick/justclick_phpunit"
set :composer_install_flags,'--no-interaction --optimize-autoloader'
set :symfony_env,           'test'
set :controllers_to_clear,  []

set :file_permissions_roles, :app
set :file_permissions_paths, ["var/logs/test", "var/cache/test"]
set :file_permissions_chmod_mode, "0777"

before "deploy:updated", "deploy:set_permissions:chmod"

Rake::Task['amqp:restart_legacy'].clear_actions()
