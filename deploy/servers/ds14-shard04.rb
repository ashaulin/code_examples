# Extended Server Syntax
# ======================
# This can be used to drop a more detailed server definition into the
# server list. The second argument is a, or duck-types, Hash and is
# used to set extended properties on the server.

# ds14 - это сервер для рассылки почты (формирования писем и отправки их на почтовые сервера)
# у него нет своих отдельных БД и у него другой крон
# После обновления кода надо рестартовать рассылки
server 'ds14.justclick.ru', user: 'apavlov', roles: %w{app mailing}


# Custom Options
# ==================

set :deploy_to,                 "/home/justclick/mirrors/www-shard04-cef"
set :linked_files_pattern,      ['']
set :linked_dirs,   [
    'errors',
    'data/compiled',
    'data/logs',
    'data/bounce',
    'data/fbl',
    'backend/logs',
    'backend/pids',
    'includes/lib/Geo/ipgeobase',
    'includes/lib/Geo/sypex',
]

set :notify_jenkins,            false
set :crontab,                   'production'
set :repo_url,                  'ssh://hg@bitbucket.org/justclickru/production'

