FROM php:8.4-fpm

RUN docker-php-ext-install intl zip

WORKDIR /app
COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --optimize-autoloader --no-scripts --no-interaction

CMD ["php-fpm"]
