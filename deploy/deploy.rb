# config valid only for current version of Capistrano
lock '3.4'

set :application,           'justclick'

set :pty,                   true

# Костыль для запуска утилит из /sbin
# При обычном входе создается интерактивная сессия и загружается содержимое /etc/profile
# Капистрано ходит неинтерактивной сессией. Советы  set :default_shell, 'bash -l' не работают
set :default_env,           { path: "/sbin:$PATH" }

set :scm,                   :hg
set :repo_url,              'ssh://hg@bitbucket.org/justclickru/trunk'

set :branch,                Shellwords.escape(ENV.fetch('branch', 'stage'))

set :composer_install_flags, '--no-scripts --no-dev --no-interaction --optimize-autoloader --no-ansi'
set :composer_roles,        :app
set :symfony_directory_structure, 3

#one of :pretty, :simpletext, :dot, or :blackhole
set :format,                :pretty
#one of :debug, :info, :warn, or :error
set :log_level,             :debug

set :ssh_options,           { :forward_agent => true, :port => 22 }

set :keep_releases,         5

set :deploy_to,             "/home/justclick/justclick"

set :confirm_migrations,    true

set :symfony_env,           'prod'
set :assets_env,            'prod'

set :linked_files,  fetch(:linked_files, []).push(
    'composer.phar',
    'includes/application.ini',
    'data/active_servers',
    'app/config/parameters.yml'
)

set :linked_files_pattern,  fetch(:linked_files_pattern, []).push(
    'backend/temp/*.csv',
    'backend/*.log',
)

set :linked_dirs,   fetch(:linked_dirs, []).push(
    'node_modules',
    'bower_components',
    'errors',
    'data/user_robo_certs',
    'media/content',
    'media/protection',
    'media/shop/goods',
    'media/shop/sign',
    'media/docx/justpay',
    'data/call_record',
    'data/check_cache',
    'data/compiled',
    'data/logs',
    'data/protection',
    'data/task_files',
    'data/templates/custom',
    'data/tmp_lead_import',
    'data/tmp_lead_import_user',
    'data/cache',
    'data/bounce',
    'data/fbl',
    'data/justpay/documents',
    'data/xml',
    'data/packets',
    'backend/logs',
    'backend/pids',
    'images',
    'includes/lib/Geo/ipgeobase',
    'includes/lib/Geo/sypex',
    'pma_jc_093',
    'var/run',
    'web/upload'
)

set :amqp_queues, {
    'justclick.leads.export_request_queue' => 1,
    'justclick.users.tariff_change_queue' => 1,
    'justclick.contacts.export_request_queue' => 1,
}

SSHKit.config.command_map[:composer] = "./composer.phar"
SSHKit.config.command_map[:service] = '/sbin/service'

namespace :deploy do
    after :finishing, 'deploy:cleanup'
end

namespace :composer do
    after :install, "symfony:build_bootstrap"
end
