FROM php:7.2.3-fpm

## Update and install packages
RUN apt-get update
RUN apt-get install -y libmcrypt-dev ffmpeg gnupg

## Install Node version 9
RUN curl -sL https://deb.nodesource.com/setup_9.x | bash
RUN apt-get install -y nodejs

RUN docker-php-ext-install pdo_mysql

## Installing Yarn
RUN curl -o- -L https://yarnpkg.com/install.sh | bash

## Installinf composer
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer