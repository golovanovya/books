FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install pdo_mysql

RUN apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
RUN echo 'xdebug.mode=develop,debug,coverage' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# composer
ENV COMPOSER_HOME=/composer
ENV PATH=./vendor/bin:/composer/vendor/bin:/root/.yarn/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/
