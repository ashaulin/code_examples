# encoding: utf-8

namespace :nginx do
    desc 'Stop nginx service'
    task :stop do
        on roles(:web) do
            execute :sudo, :service, 'nginx', 'stop'
        end
    end

    desc 'Start nginx service'
    task :start do
        on roles(:web) do
            execute :sudo, :service, 'nginx', 'start'
        end
    end

    desc 'Restart nginx service'
    task :restart do
        on roles(:web) do
            execute :sudo, :service, 'nginx', 'restart'
        end
    end

    desc 'Reload nginx service'
    task :reload do
        on roles(:web) do
            execute :sudo, :service, 'nginx', 'reload'
        end
    end
end

namespace :deploy do
    after :published, 'nginx:reload'
end
