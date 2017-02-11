# encoding: utf-8
require 'erb'

namespace :crontab do
    desc 'Install crontab'
    task :install do
        on roles(:app) do |server|
            if server.properties.cron
                crontab_file = "crontab/#{fetch(:crontab)}.erb"
                webinar_path = server.properties.webinar_path

                template = ERB.new(File.new(crontab_file).read).result(binding)
                execute :echo, Shellwords.escape(template.force_encoding('binary')), '|', 'EDITOR=tee', :crontab, '-e'
            end
        end
    end

    desc 'Show crontab'
    task :show do
        on roles(:all), in: :sequence do
            execute :crontab, '-l'
        end
    end
end

namespace :load do
    task :defaults do
        set :crontab, 'default'
    end
end

namespace :deploy do
    after :updated, 'crontab:install'
end
