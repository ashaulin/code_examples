# Extended Server Syntax
# ======================
# This can be used to drop a more detailed server definition into the
# server list. The second argument is a, or duck-types, Hash and is
# used to set extended properties on the server.

# Морда сайта и staffs
server 'vs03.justclick.ru', user: 'reload-user', roles: %w{app web amqp}
# рабочие сервера пользователей
server 'vs04.justclick.ru', user: 'reload-user', roles: %w{app web amqp}, :cron => true
server 'www-shard05-ghi.justclick.ru', user: 'reload-user', roles: %w{app web amqp}, :cron => true
server 'www-shard06-kl.justclick.ru', user: 'reload-user', roles: %w{app web amqp}, :cron => true
server 'www-shard07-jm.justclick.ru', user: 'reload-user', roles: %w{app web amqp}, :cron => true
server 'www-shard08-nop.justclick.ru', user: 'reload-user', roles: %w{app web amqp}, :cron => true
server 'www-shard09-s.justclick.ru', user: 'reload-user', roles: %w{app web amqp}, :cron => true
server 'www-shard10-qrtu.justclick.ru', user: 'reload-user', roles: %w{app web amqp}, :cron => true
server 'www-shard11-vwxyz.justclick.ru', user: 'reload-user', roles: %w{app web amqp}, :cron => true
server 'www-shard01-0-9.justclick.ru', user: 'reload-user', roles: %w{app web amqp}, :cron => true
server '88.198.241.228', user: 'reload-user', roles: %w{app web amqp}, :cron => true
server 'www-shard03-bd.justclick.ru', user: 'reload-user', roles: %w{app web amqp}, :cron => true
server 'www-shard04-cef.justclick.ru', user: 'reload-user', roles: %w{app web amqp}, :cron => true

# это почтовые сервера, на них сейчас нужно обновление только одного файла /backend/maillog_parser3
#server 'ds08.justclick.ru', user: 'reload-user', roles: %w{app}
#server 'vs01.justclick.ru', user: 'reload-user', roles: %w{app}
#server 'vs02.justclick.ru', user: 'reload-user', roles: %w{app}

# Custom Options
# ==================

set :notify_jenkins,            false
set :crontab,                   'production'
set :repo_url,                  'ssh://hg@bitbucket.org/justclickru/production'

set :amqp_queues, {
    'justclick.leads.export_request_queue' => 3,
    'justclick.users.tariff_change_queue' => 1,
    'justclick.contacts.export_request_queue' => 3,
}
