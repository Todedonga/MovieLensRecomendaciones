FROM php:8.2-apache

# Instalar extensiones de PHP
RUN docker-php-ext-install mysqli

# Instalar Python, pip y TODAS las dependencias de compilación requeridas
# Incluye: build-essential, python3-dev (cabeceras), 
#          libblas-dev, liblapack-dev (Álgebra Lineal),
#          gfortran y libgfortran5 (Compilador Fortran/Enlazado)
#          zlib1g-dev (Compresión, necesaria para algunas dependencias)
RUN apt-get update && \
    apt-get install -y \
    python3 \
    python3-pip \
    **build-essential** \
    **python3-dev** \
    **libblas-dev** \
    **liblapack-dev** \
    **gfortran** \
    **libgfortran5** \
    **zlib1g-dev** \
    && rm -rf /var/lib/apt/lists/*

# Crear alias para que "python" funcione como comando
RUN ln -s /usr/bin/python3 /usr/bin/python

# Copiar archivos del proyecto
# ESTE PASO DEBE ESTAR AQUÍ, ANTES DE EJECUTAR pip install
COPY . /var/www/html/

# Instalar dependencias de requirements.txt (si existe)
# LÍNEA 23 DEL ERROR
RUN if [ -f /var/www/html/requirements.txt ]; then pip3 install -r /var/www/html/requirements.txt; fi

# Permisos para Apache
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80