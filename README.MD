# Prossimo backend #

Backend software to handle frontend requests, external API-calls, authentification.

Build on Simfony2 framework (v2.7.5)

## Requirements ##

PHP5.5 or later

NGinx

PostgreSQL9.3 (Or MySQL)

## Installation ##

- Create project folder

```
#!bash

$ mkdir /var/www/prossimo

```

- Clone latest project 

```
#!bash

$ git clone https://github.com/misha909/prossimo.git .

```

- Set permissions

```
#!bash

$ sudo chown -R www-data:www-data /var/www/prossimo

$ HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
$ sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
$ sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs

```

- Create database

```
#!bash

$ sudo passwd postgres
$ enter new password

$ sudo -u postgres psql

```

```
#!sql

$ ALTER ROLE postgres WITH password 'your password';

$ CREATE DATABASE prossimo;

$ \q

```

- Setup vendor libraries 

```
#!bash

$ composer install

```

- Create schema, migrate it to latest version and load fixtures

```
#!bash

$ php app/console doctrine:schema:drop --force && php app/console doctrine:schema:update --force && php app/console doctrine:fixtures:load

```

- Create superuser

```
#!bash

$ php app/console fos:user:create admin admin@prossimo.us P@$$W0RD --super-admin

```


## Configuration ##

** NGinx config **

```
server {
        root /var/www/prossimo/web/;
        server_name prossimo.local *.prossimo.local;

        location / {
                add_header 'Access-Control-Allow-Origin' '*';
                try_files $uri /index.php$is_args$args;
        }
        #DEV
        location ~ ^/(index|app_dev|config)\.php(/|$) {
                fastcgi_pass unix:/var/run/php5-fpm.sock;
                fastcgi_split_path_info ^(.+\.php)(/.*)$;
                include fastcgi_params;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_nam$
                fastcgi_param HTTPS off;
        }
		
		#PROD
        location ~ ^/app\.php(/|$) {
                fastcgi_pass unix:/var/run/php5-fpm.sock;
                fastcgi_split_path_info ^(.+\.php)(/.*)$;
                include fastcgi_params;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_nam$
                fastcgi_param HTTPS off;

                internal;
        }

        error_log /var/log/nginx/prossimo_error.log;
        access_log /var/log/nginx/prossimo_access.log;
}
```
