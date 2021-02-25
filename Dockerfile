FROM php:8.0.2-fpm

ENV PATH "$PATH:/var/www/html/vendor/bin"

# curl для установки composer
# libzip-dev zip для установки zip extension
RUN apt-get update \
    && apt-get install -y \
        curl \
        libzip-dev \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip \
    \
    && groupadd -g 1000 www-user \
    && useradd -u 1000 -ms /bin/bash -g www-user www-user \
    \
    && apt -qy install $PHPIZE_DEPS \
    && pecl install xdebug-3.0.2 \
    && docker-php-ext-enable zip xdebug \
    && echo "alias xphp=\"XDEBUG_CONFIG=\\\"idekey=\$XDEBUG_IDE_KEY\\\" PHP_IDE_CONFIG=\\\"serverName=\$XDEBUG_SERVER\\\" php -dxdebug.client_host=host.docker.internal -dxdebug.mode=debug \"" >> /home/www-user/.bash_aliases \
    \
    && curl --silent --show-error https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer


EXPOSE 9000
CMD ["sh", "-c", " php-fpm"]
