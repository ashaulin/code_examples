# Extended Server Syntax
# ======================
# This can be used to drop a more detailed server definition into the
# server list. The second argument is a, or duck-types, Hash and is
# used to set extended properties on the server.

server 'vs03.justclick.ru', user: 'reload-user', roles: %w{app amqp}

# Custom Options
# ==================

set :notify_jenkins,            false
set :crontab,                   'www'
set :repo_url,                  'ssh://hg@bitbucket.org/justclickru/production'
