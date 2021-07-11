FROM php:7.4-apache
 
RUN apt-get update \
    && apt-get -y install git zip unzip libzip-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-install mysqli \
    && a2enmod rewrite \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

WORKDIR /var/www/html/

COPY composer.json composer.lock ./
RUN composer install

COPY . .

EXPOSE 80
CMD ["apachectl", "-D", "FOREGROUND"]