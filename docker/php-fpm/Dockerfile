FROM php:8.1-fpm

# Install software
RUN apt update && apt install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

COPY php.ini /usr/local/etc/php/conf.d/
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

VOLUME /var/www/dumpit
WORKDIR /var/www/dumpit

EXPOSE 9000

CMD ["php-fpm", "-F"]