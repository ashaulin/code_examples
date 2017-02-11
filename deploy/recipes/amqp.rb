# encoding: utf-8

namespace :amqp do
    desc 'Restart legacy AMQP workers'
    task :restart_legacy do
        on roles(:amqp) do
            within release_path do
                execute :bash, 'backend/bin/restart_consumer.sh'
            end
        end
    end

    desc 'Restart dead AMQP workers'
    task :restart_dead do
        on roles(:amqp) do
            within release_path do
                execute :php, symfony_console_path, "daemon", "restart_dead", "--instance-name=*"
            end
        end
    end

    desc 'Stop AMQP workers'
    task :stop do
        on roles(:amqp) do
            within release_path do
                execute :php, symfony_console_path, "daemon", "stop", "--instance-name=*", "--nowait"
            end
        end
    end

    desc 'Start AMQP workers'
    task :start do
        on roles(:amqp) do
            within release_path do
                fetch(:amqp_queues).each do | queue_name, n |
                    for i in (1..n)
                        execute :php, symfony_console_path, "daemon", "start", "--instance-name=#{queue_name}_#{i}", "--options=--verbose", 'event-band:dispatch', queue_name
                    end
                end
            end
        end
    end

    desc 'Configure AMQP queues'
    task :configure do
        on roles(:amqp) do
            within release_path do
                execute :php, symfony_console_path, "event-band:setup"
            end
        end
    end
end

namespace :deploy do
    after :updated, 'amqp:restart_legacy'
end

namespace :doctrine do
    namespace :migrations do
        before :migrate, 'amqp:stop'
        after :migrate, 'amqp:configure'
        after :migrate, 'amqp:start'
    end
end
