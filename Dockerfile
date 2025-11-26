FROM php:8.2-fpm

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    zip unzip curl libpng-dev libonig-dev libxml2-dev \
    libzip-dev libpq-dev && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Копируем проект
WORKDIR /var/www/html
COPY . .

RUN composer install

# Права
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
