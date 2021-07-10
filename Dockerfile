FROM php:7.2-apache
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
WORKDIR /var/www/html/
COPY composer.json composer.lock ./
RUN composer install
COPY . .
CMD ["httpd", "-D", "FOREGROUND"]