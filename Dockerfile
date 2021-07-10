FROM php:apache
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
WORKDIR /var/www/html/
COPY . .
RUN composer install
CMD ["httpd", "-D", "FOREGROUND"]