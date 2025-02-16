# Используем официальный образ PHP с Apache
FROM php:8.2-apache

# Устанавливаем необходимые системные зависимости
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копируем исходный код в контейнер
COPY . /var/www/html

# Устанавливаем рабочий каталог
WORKDIR /var/www/html

# Устанавливаем зависимости Laravel
RUN composer install --optimize-autoloader --no-dev

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Включаем mod_rewrite для Apache
RUN a2enmod rewrite

# Копируем конфигурацию Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Открываем порт 80
EXPOSE 80
