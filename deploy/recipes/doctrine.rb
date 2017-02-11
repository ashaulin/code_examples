# encoding: utf-8

namespace :doctrine do
    namespace :migrations do
        task :migrate_db, :db_name do |task, args|
            on roles(:all), in: :sequence do |server|
                within release_path do
                    hasNew = capture("SYMFONY_ENV=#{fetch(:symfony_env)}", :php, symfony_console_path, 'doctrine:migrations:status', "--em=#{args[:db_name]}", '--no-ansi', "| grep 'New Migrations'  | awk '{ print $4 }'")
                    if "#{hasNew}".to_i > 0
                        if fetch(:confirm_migrations)
                            symfony_console('doctrine:migrations:migrate', "--em=#{args[:db_name]} --no-interaction --allow-no-migration --dry-run")
                            ask :answer, "Применить миграции для #{args[:db_name]} на сервере #{server.hostname} (y/n)"
                        else
                            set :answer, 'y'
                        end
                        if fetch(:answer) == 'y'
                            symfony_console('doctrine:migrations:migrate', "--em=#{args[:db_name]} --no-interaction --allow-no-migration")
                        end
                    end
                end
                Rake::Task[task].reenable
            end
        end

        task :migrate_shared do
            on roles(:all) do |server|
                invoke 'doctrine:migrations:migrate_db', 'main'
                invoke 'doctrine:migrations:migrate_db', 'auth'
                invoke 'doctrine:migrations:migrate_db', 'stats'
                invoke 'doctrine:migrations:migrate_db', 'logs'
                if server.properties.no_logger != true
                    invoke 'doctrine:migrations:migrate_db', 'logger'
                end
            end
        end

        task :migrate_private do
            on roles(:all) do
                invoke 'doctrine:migrations:migrate_db', 'default'
                invoke 'doctrine:migrations:migrate_db', 'shard1'
                invoke 'doctrine:migrations:migrate_db', 'serial'
            end
        end

        desc 'Run Doctrine 2 migrations'
        task :migrate do
            on roles(:shared_db) do
                invoke 'doctrine:migrations:migrate_shared'
            end
            on roles(:db) do
                invoke 'doctrine:migrations:migrate_private'
            end
        end
    end
end

namespace :deploy do
    after :updated, 'doctrine:migrations:migrate'
end
