# encoding: utf-8

namespace :assets do
    desc 'Install npm deps'
    task :npm do
        on roles(:web) do
            within release_path do
                execute :npm, 'install'
            end
        end
    end
    desc 'Install deps'
    task :install do
        invoke 'assets:npm'
    end
    desc 'Build assets'
    task :build do
        on roles(:web) do
            within release_path do
                execute :npm, 'run', fetch(:assets_env)
            end
        end
    end
end

namespace :deploy do
    before :updated, 'assets:install'
    before :updated, 'assets:build'
end
