# config valid for current version and patch releases of Capistrano
lock "~> 3.10.0"

set :application, "newsreader-laravel"
set :repo_url, "https://github.com/puntik/newsreader-demo.git"

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, "/home/puntik/www/newsreader.cz/app3"

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: "log/capistrano.log", color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# append :linked_files, "config/database.yml", "config/secrets.yml"
append :linked_files, ".env"

# Default value for linked_dirs is []
# append :linked_dirs, "log", "tmp/pids", "tmp/cache", "tmp/sockets", "public/system"

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for local_user is ENV['USER']
# set :local_user, -> { `git config user.name`.chomp }

# Default value for keep_releases is 5
set :keep_releases, 3

# Uncomment the following to require manually verifying the host key before first deploy.
# set :ssh_options, verify_host_key: :secure

namespace :composer do
	desc "Provide composer actions"
		task :install do
			on roles(:web), in: :sequence, wait: 5 do
				within release_path do
				# composer magic
				execute "cd #{release_path}; /usr/local/bin/composer install --no-dev"
        	end
    	end
	end
end

namespace :directories do
	desc "Check permission for storage directories"
		task :set_privileges do
			on roles(:web) do
				within release_path do
					execute "cd #{release_path}/storage; find -type d -exec chmod 777 {} \\;"
			end
		end
	end
end

namespace :artisan do
	desc "Provide artisan magic"
		task :migrate do
			on roles(:web), in: :sequence, wait: 5 do
				within release_path do execute "cd #{release_path}; php artisan migrate -q"
			end
		end
	end
end

namespace :deploy do
	after :updated, "composer:install"
	after :updated, "directories:set_privileges"
	after "composer:install", "artisan:migrate"
end
