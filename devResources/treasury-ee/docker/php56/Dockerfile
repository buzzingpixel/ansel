FROM php:5.6.40-fpm

COPY ./docker/.profile /root/.profile
COPY ./docker/.profile /root/.bashrc

RUN apt-get update
RUN apt-get install -my --no-install-recommends libmagick++-dev
RUN apt-get install -my --no-install-recommends libmagickwand-dev
RUN apt-get install -my --no-install-recommends libonig-dev
RUN apt-get install -my --no-install-recommends libssl-dev
RUN apt-get install -my --no-install-recommends libwebp-dev
RUN apt-get install -my --no-install-recommends libxml2
RUN apt-get install -my --no-install-recommends libxml2-dev
RUN apt-get install -my --no-install-recommends libxpm-dev
RUN apt-get install -my --no-install-recommends libzip-dev
RUN apt-get install -my --no-install-recommends openssl
RUN apt-get install -my --no-install-recommends unzip
RUN apt-get install -my --no-install-recommends libjpeg-dev
RUN apt-get install -my --no-install-recommends libpng-dev
RUN apt-get clean
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/ --with-png-dir=/usr/ --with-jpeg-dir=/usr/ --with-webp-dir=/usr --with-gd && docker-php-ext-install gd
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install bz2
RUN docker-php-ext-install exif
RUN docker-php-ext-install iconv
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install xmlrpc
RUN docker-php-ext-install zip
RUN docker-php-ext-install mysqli;
RUN pecl install imagick
RUN docker-php-ext-enable imagick
RUN pecl install xdebug-2.5.5;
RUN docker-php-ext-enable xdebug;

COPY ./docker/php56/php-custom-config.ini /usr/local/etc/php/conf.d/php-custom-config.ini
COPY ./docker/php56/php-config-dev.ini /usr/local/etc/php/conf.d/php-env-config.ini
COPY ./docker/php56/fpm-www.conf /usr/local/etc/php-fpm.d/www.conf
