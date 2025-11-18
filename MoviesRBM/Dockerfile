# Imagen base con Apache + PHP 8.2
FROM php:8.2-apache

# Instalar extensiones necesarias de PHP
RUN docker-php-ext-install mysqli

# Instalar Python dentro del contenedor
RUN apt-get update && apt-get install -y python3 python3-pip

# Copiar los archivos del proyecto al servidor Apache
COPY . /var/www/html/

# Dar permisos a Apache
RUN chown -R www-data:www-data /var/www/html

# Instalar dependencias de Python si las hay
# (Si tienes requirements.txt)
RUN if [ -f /var/www/html/requirements.txt ]; then pip3 install -r /var/www/html/requirements.txt; fi

# Exponer el puerto por donde Render accede
EXPOSE 80