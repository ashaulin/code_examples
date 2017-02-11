namespace :mailing do
    task :restart_mailing, :pids, :path do |task, args|
        on roles(:mailing) do
            within release_path do
                execute :test, '-f', args[:pid], '&&', :awk, '{print}', *args[:pids], '|', :xargs, '--no-run-if-empty', :kill
            end
            within "#{release_path}/../../" do
                execute *args[:path]
            end
        end
        Rake::Task[task].reenable
    end

    desc "Перезапустить рассылки"
    task :restart do
        on roles(:mailing) do
            invoke 'mailing:restart_mailing', ['backend/pids/anonsd.pid'], ['./start_anonsd']
            invoke 'mailing:restart_mailing', ['backend/pids/serial.pid', 'backend/pids/serial_pids.pid'], ['./start_Serial']
        end
    end
end

namespace :deploy do
    after :updated, 'mailing:restart'
end
