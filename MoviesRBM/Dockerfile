FROM php:8.2-apache

RUN docker-php-ext-install mysqli

RUN apt-get update && apt-get install -y python3 python3-pip

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
