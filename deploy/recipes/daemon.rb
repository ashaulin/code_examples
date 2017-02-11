namespace :daemon do
    task :restart_daemon, :pids, :path do |task, args|
        on roles(:backend) do
            within release_path do
                execute :test, '-f', args[:pid], '&&', :awk, '{print}', *args[:pids], '|', :xargs, '--no-run-if-empty', :kill, ';', *args[:path]
            end
        end
        Rake::Task[task].reenable
    end

    desc "Перезапустить демонов"
    task :restart do
        on roles(:backend) do
            invoke 'daemon:restart_daemon', ['backend/pids/anonsd.pid'], [:php, 'backend/anonsd']
            invoke 'daemon:restart_daemon', ['backend/pids/serial.pid', 'backend/pids/serial_pids.pid'], [:php, 'backend/bin/cli.php', 'Serial']
        end
    end
end

namespace :deploy do
    after :updated, 'daemon:restart'
end
