FROM php:8.2-apache

# Instalar extensiones de PHP
RUN docker-php-ext-install mysqli

# Instalar Python, pip y las dependencias de compilación/álgebra lineal
RUN apt-get update && \
    apt-get install -y \
    python3 \
    python3-pip \
    build-essential \
    python3-dev \
    **libblas-dev** \
    **liblapack-dev** \
    && rm -rf /var/lib/apt/lists/*

# Crear alias para que "python" funcione como comando
RUN ln -s /usr/bin/python3 /usr/bin/python

# Copiar archivos del proyecto
COPY . /var/www/html/

# Instalar dependencias de requirements.txt (si existe)
RUN if [ -f /var/www/html/requirements.txt ]; then pip3 install -r /var/www/html/requirements.txt; fi

# Permisos para Apache
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80