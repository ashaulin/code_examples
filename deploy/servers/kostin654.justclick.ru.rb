# Extended Server Syntax
# ======================
# This can be used to drop a more detailed server definition into the
# server list. The second argument is a, or duck-types, Hash and is
# used to set extended properties on the server.

server 'vs33.justclick.ru', user: 'reload-user', roles: %w{app web backend amqp db}, :cron => true

# Custom Options
# ==================

set :confirm_migrations,        false
set :notify_jenkins,            true
set :crontab,                   'kostin654'
