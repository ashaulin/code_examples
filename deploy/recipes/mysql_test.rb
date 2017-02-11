# encoding: utf-8

namespace :mysql_test do
    desc 'Start custom mysql instance'
    task :autostart do
        on roles(:db) do
            if fetch(:autostart_mysql) == true
                invoke 'mysql_test:stop'
                invoke 'mysql_test:start'
            end
        end
    end

    task :start do | task |
        on roles(:db) do
            within "#{shared_path}/mysql" do
                execute :bash, 'mysql_start'
            end
            Rake::Task[task].reenable
        end
    end

    task :stop do | task |
        on roles(:db) do
            within "#{shared_path}/mysql" do
                execute :bash, 'mysql_stop'
            end
            Rake::Task[task].reenable
        end
    end

    desc 'Apply migrations to db archive'
    task :migrate do
        on roles(:db) do
            set :branch, 'stage'
            set :autostart_mysql,  true
            invoke 'deploy'
            invoke 'mysql_test:stop'
            within "#{shared_path}/mysql" do
                now = env.timestamp.strftime("%Y%m%d%H%M%S")
                execute :tar, '-czf',  "jcstage.co.#{now}.mysql.tar.gz", '--directory=database', 'mysql-db'
                execute :rm, 'jcstage.co.mysql.tar.gz'
                execute :ln, '-s', "jcstage.co.#{now}.mysql.tar.gz", 'jcstage.co.mysql.tar.gz'
                execute :ls, '--sort=time', 'jcstage.co.*.mysql.tar.gz', '|', :grep, Shellwords.escape('.*'), '|', :tail, '-n', "+#{fetch(:keep_releases)+1}", '|', :xargs, '--no-run-if-empty', :rm
            end
        end
    end
end

namespace :load do
    task :defaults do
        on roles(:db) do
            set :autostart_mysql, false
        end
    end
end

namespace :doctrine do
    namespace :migrations do
        before :migrate, 'mysql_test:autostart'
    end
end
