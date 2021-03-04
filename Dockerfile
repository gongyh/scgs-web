FROM quay.io/gongyh/php7.3.8-apache
#FROM php:7.3.8-apache

RUN apt-get update -y \
    && apt-get install -y --no-install-recommends unzip locales nodejs \
       libwebp-dev libjpeg-dev libxpm-dev libfreetype6-dev libzip-dev \
       iputils-ping libcurl4-openssl-dev pkg-config libssl-dev \
    && apt-get autoremove -y && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /app

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/sbin/composer \
    && chmod +x /usr/local/sbin/composer

# install pecl extension
RUN pecl install mongodb && docker-php-ext-enable mongodb && rm -rf /tmp/pear

# install GD
RUN docker-php-ext-configure gd --with-png-dir \
    --with-jpeg-dir --with-xpm-dir \
    --with-webp-dir --with-freetype-dir \
    && docker-php-ext-install -j$(nproc) gd

# install more php extensions
RUN docker-php-ext-install -j$(nproc) zip bcmath mysqli pdo_mysql

# install php redis extension
RUN pecl install redis && docker-php-ext-enable redis && rm -rf /tmp/pear

# set locale to utf-8
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && locale-gen
ENV LANG='en_US.UTF-8' LANGUAGE='en_US:en' LC_ALL='en_US.UTF-8'

COPY . /app

RUN chown -R www-data:www-data /app && a2enmod rewrite

RUN rm -f composer.lock && composer install && rm -rf /root/.composer/cache
RUN curl -L https://www.npmjs.com/install.sh | sh && rm -f package-lock.json \
    && npm install -y && npm run production && rm -rf /root/.npm

COPY vhost.conf /etc/apache2/sites-available/000-default.conf

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && sed -i 's/post_max_size.*$/post_max_size = 2000M/g' /usr/local/etc/php/php.ini \
    && sed -i 's/upload_max_filesize.*$/upload_max_filesize = 2000M/g' /usr/local/etc/php/php.ini

RUN rm -rf /tmp/* /var/tmp/*

EXPOSE 80

