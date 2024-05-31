ARG PHP_VERSION=8.3.6

FROM php:${PHP_VERSION}-fpm-alpine

## Copy builder user to mac system as dev
ARG USER_ID
ARG GROUP_ID
RUN echo "dev:x:$USER_ID:$USER_ID::/home/dev:" >> /etc/passwd && \
    echo "dev:!:$(($(date +%s) / 60 / 60 / 24)):0:99999:7:::" >> /etc/shadow  && \
    echo "dev:x:$USER_ID:" >> /etc/group  && \
    mkdir /home/dev && chown dev: /home/dev

# essencial stuffs
RUN apk --no-cache --update add  \
    $PHPIZE_DEPS \
    bash

# Add composer manager
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
	php composer-setup.php --install-dir=/usr/local/bin --filename=composer

USER dev

WORKDIR /var/www/html
COPY workspace/. ./
RUN composer install

# Image Cleaner
RUN rm -rf /var/cache/apk/*

EXPOSE 9000
CMD ["php-fpm"]



