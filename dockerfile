FROM php:8.2-fpm

# Cài đặt các dependencies cần thiết
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www

# Sao chép mã nguồn Laravel vào container
COPY . .

# Cấp quyền cho storage và bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Cài đặt các package PHP cần thiết
RUN composer install --no-dev --optimize-autoloader

# Expose cổng PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
