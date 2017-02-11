# encoding: utf-8

namespace :php do
    desc 'Stop php5-fpm service'
    task :stop do
        on roles(:web) do
            execute :sudo, :service, 'php-fpm', 'stop', "; true"
        end
    end

    desc 'Start php5-fpm service'
    task :start do
        on roles(:web) do
            execute :sudo, :service, 'php-fpm', 'start', "; true"
        end
    end

    desc 'Restart php5-fpm service'
    task :restart do
        on roles(:web) do
            execute :sudo, :service, 'php-fpm', 'restart', "; true"
        end
    end

    desc 'Reload php5-fpm service'
    task :reload do
        on roles(:web) do
            execute :sudo, :service, 'php-fpm', 'reload', "; true"
        end
    end
end

namespace :deploy do
    after :published, 'php:reload'
end
