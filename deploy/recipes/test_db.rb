# encoding: utf-8

namespace :test_db do
    desc 'Удалить все тестовые базы данных'
    task :drop do
        on roles(:test_db) do
            within release_path do
                # -e имеет приоритет над SYMFONY_ENV но я не стал на это завязываться
                execute 'SYMFONY_ENV=test', 'PARALLEL=false', 'bin/for_each_conn', 'doctrine:database:drop', '--force', '-e', 'test', ';', 'true'
            end
        end
    end

    desc 'Создать пустые тестовые базы данных'
    task :create do
        on roles(:test_db) do
            within release_path do
                execute 'SYMFONY_ENV=test', 'PARALLEL=true', 'bin/for_each_conn', 'doctrine:database:create', '-e', 'test'
            end
        end
    end

    desc 'Синхроинизировать тестовую схему бд'
    task :update_schema do
        on roles(:test_db) do
            within release_path do
                execute 'SYMFONY_ENV=test', 'PARALLEL=true', 'bin/for_each_em', 'doctrine:schema:update', '--force', '-e', 'test'
            end
        end
    end

    desc 'Перестроить все бд'
    task :reset do
        on roles(:test_db) do
            invoke 'test_db:drop'
            invoke 'test_db:create'
            invoke 'test_db:update_schema'
        end
    end
end

namespace :deploy do
    before :updated, 'test_db:reset'
end
