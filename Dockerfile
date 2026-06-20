FROM php:8.2-cli

# Instalamos librerías del sistema incluyendo oniguruma para mbstring
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libgd-dev \
    libzip-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    unzip \
    && docker-php-ext-install pdo pgsql pdo_pgsql mbstring xml curl gd zip bcmath \
    && apt-get clean

# Instalamos Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiamos el proyecto
WORKDIR /app
COPY . .

# Instalamos dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Exponemos el puerto
EXPOSE 8080

# Comando de inicio
CMD php artisan config:cache && php artisan route:cache && php artisan serve --host=0.0.0.0 --port=8080