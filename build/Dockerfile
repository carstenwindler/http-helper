FROM php:7.2-cli

RUN apt-get update -y \
    && apt-get install -qqy zlib1g-dev libicu-dev g++ wget gnupg2 openssh-client curl libpng-dev git libxml2-dev \
    && docker-php-source extract \
    && pecl install xdebug redis \
    && docker-php-ext-enable xdebug redis \
    && docker-php-source delete \
    && docker-php-ext-install pdo_mysql intl zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /tmp/*

CMD ["php", "-a"]

WORKDIR /app

EXPOSE 9000
