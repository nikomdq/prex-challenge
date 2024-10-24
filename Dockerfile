# Usa la imagen oficial de PHP con las extensiones necesarias
FROM php:8.2-fpm

# Instala las dependencias necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo en /var/www
WORKDIR /var/www

# Copia los archivos del proyecto al contenedor
COPY . .

# Instala las dependencias de PHP usando Composer
RUN composer install --no-dev --optimize-autoloader

# Establece los permisos correctos para la carpeta de almacenamiento y cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expone el puerto 9000 para PHP-FPM
EXPOSE 9000

# Comando de inicio para PHP-FPM
CMD ["php-fpm"]
