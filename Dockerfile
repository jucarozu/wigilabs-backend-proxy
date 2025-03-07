FROM php:7.4-fpm

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Copy code and settings
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Expose port and execute
EXPOSE 8080
CMD ["php-fpm"]