# encoding: utf-8

# Сохраняем номер ревизии в репозитории, для отображениии в футере страниц

namespace :cvs_rev do
    desc 'Reload php5-fpm service'
    task :save do
        on roles(:web) do
            within repo_path do
                rev = capture(:hg, 'log', "--rev #{fetch(:branch)}", '--template "{rev}"')
                execute :echo, "#{rev} > #{release_path}/rev"
            end
        end
    end
end

namespace :deploy do
    after :set_current_revision, 'cvs_rev:save'
end
