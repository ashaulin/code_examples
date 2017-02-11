require "net/http"
require "uri"

namespace :jenkins do
    task :notify do
        on roles(:all) do |server|
            if fetch(:notify_jenkins) && ENV.fetch('notify_jenkins', 'true') != 'false'
                uri = URI.parse("http://api:ab35ef37a403501094dbf83a7a144c06@vs97.justclick.ru:8080/job/JustclickDeployNotify/buildWithParameters")
                uri.query = URI.encode_www_form({
                    :token => 'r6AppqRBZisIafqbQJmo', :server => server.hostname, :branch => fetch(:branch), :cause => 'cap cli deploy'
                });
                puts Net::HTTP.get(uri);
            end
        end
    end
end

namespace :deploy do
    after :finishing, 'jenkins:notify'
end
