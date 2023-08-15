FROM php:5.6-apache

# Use the default production configuration
#RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN echo "deb http://archive.debian.org/debian stretch main contrib non-free" > /etc/apt/sources.list

RUN apt-get update && apt-get install -y \
        libpq-dev \
        libssl-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libxml2-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd iconv mcrypt

RUN docker-php-ext-install calendar && docker-php-ext-enable calendar

RUN docker-php-ext-install mysql mysqli pdo pdo_mysql && docker-php-ext-enable mysql mysqli pdo pdo_mysql

RUN docker-php-ext-install zip && docker-php-ext-enable zip

RUN docker-php-ext-install soap && docker-php-ext-enable soap