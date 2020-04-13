# Bizzmo
Based on laravel framework

## Useful links
https://aws.amazon.com/premiumsupport/knowledge-center/monitor-vpn-with-cloudwatch-alarms/


## Environment Preparation
- In `bizzmo` directory: 
    - Copy `.env.local` file and rename it to `.env`
    - Change config for username and password of database to be `homestead` and `secret` respectively in `.env` file
    - Move / Copy Vagrantfile to `Homestead` directory and override the existing one

- In `Homestead` directory: 
    - Change database name to `projectx` in `Homestead.yaml` file under **databases** section
    - Change **map** value under **folders** section to be the path of `bizzmo` directory
    - `vagrant up --provision`
    - `vagrant ssh`
    - `cd code`
    - `composer install`
    - `composer update`
    - `npm install`
    - Run Migrations: `php artisan migrate`
    - Run Seeds: `php artisan db:seed`

- To set any environment variable for ubuntu machine cmd: `echo "export VAR_NAME=var_value" >> ~/.profile`
- To make your new environment variables active in the same shell, run `source ~/.profile`

## SAP Preparation 
- In `Homestead` directory: 
	- Copy `nwrfcsdk` directory downloaded from SAP to `Homestead` directory
	- Clone `php7-sapnwrfc`
	```sh
    git clone https://github.com/gkralik/php7-sapnwrfc.git
    ```
### SAP Manual Installation [**In case Vagrantfile in the repo is not used**]
- You should copy `nwrfcsdk` dir under `/usr` dir, e.g. `/usr/sap/nwrfcsdk`
- You may need to install `php7.2-dev`
	```sh
	sudo apt-get update 
	sudo apt install php7.2-dev
	```
- Then run the following commands:
	```sh
	cd php7-sapnwrfc/
	phpize
	./configure
	make
	sudo make install
	sudo bash -c 'echo "/usr/sap/nwrfcsdk/lib" > /etc/ld.so.conf.d/nwrfcsdk.conf'
	sudo ldconfig
	```

- Append: `extension=sapnwrfc.so` to `php.ini` file under `[PHP]` Section
	```sh
    sudo nano /etc/php/7.2/cli/php.ini
	```

	[**OPTIONAL STEPS**]
	- To make sure extension is properly installed, run `php -m | grep "sap"`, you should see `sapnwrfc` in the output
	- You should also be able to run `php -v` without any errors

### SAP installation for php-fpm
- Create `sapnwrfc.ini` file under `mods-avaiable` directory
	```sh
	cd /etc/php/<VERSION>/mods-avaiable
	touch sapnwrfc.ini
	```
- Append `extension=sapnwrfc.so` to `sapnwrfc.ini` file
- Check available modules for web requests, we can check out the `fpm` section, and specifically the `conf.d` directory
	```sh
	ls -lah /etc/php/<VERSION>/conf.d
	```
- Add new `symlinks` to the `mods-available/sapnwrfc.ini`
	```sh
	sudo ln -s /etc/php/<VERSION>/mods-available/sapnwrfc.ini /etc/php/<VERSION>/fpm/conf.d/20-sapnwrfc.ini
	```
- Now if you check the last command again you can see new link created to `/etc/php/<VERSION>/mods-available/sapnwrfc.ini`
	```sh
	ls -lah /etc/php/<VERSION>/conf.d
	```
- Reload php-fpm service
	```sh
	sudo service php<VERSION>-fpm reload
	```

# Artisian Commands

- Create new migration
	```sh
	php artisan make:migration {{Migration Name}}
	```

- Create DB seed
	```sh
	php artisan make:seeder {{Seed Name}}
	```

- Rolling back migrations and re-migrate a limited number of migrations
	```sh
	php artisan migrate:refresh --step={{Number of Migrations}}
	```	

## Schedule
- To start laravel task scheduling on ubuntu you need to add a cron job
	```sh
		crontab -e
	```
- to open your cron job file, then add
	```sh
		* * * * * php /var/www/stockhit/artisan schedule:run 1>> /dev/null 2>&1
	```
- This Cron will call the Laravel command scheduler every minute. Then, Laravel evaluates your scheduled tasks and runs the tasks that are due.

# Faced issues
 - When you run the DB seed command 
	```sh
	php artisan db:seed
	```
 	and get this error `[ReflectionException] Class *****Seeder does not exist`. you need to run the following command to generate a new class map
 	```sh
	composer dump-autoload
	```
	then run the DB seed command again

 - SapConnection Class not found error, run the following commands:
		```sh
		sudo ldconfig
		sudo service php<VERSION>-fpm reload
		```
 - When you run config clear
	```sh
	php artisan config:clear
	```
	after which you get the 'class config not found' error when you try to run the site. Delete config.php from {root}/bootstrap/cache and then run
	```sh
	php artisan optimize
	```
