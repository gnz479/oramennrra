# Usa una imagen base de PHP con Apache
FROM php:8.1-apache

# Instala extensiones y herramientas necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia los archivos de tu proyecto al contenedor
COPY . /var/www/html

# Establece los permisos para los archivos de Laravel
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Instala las dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Expone el puerto de Apache
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
