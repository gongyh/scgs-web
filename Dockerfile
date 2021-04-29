FROM quay.io/gongyh/php7.3.8-apache
#FROM php:7.3.8-apache

RUN apt-get update -y && mkdir -p /usr/share/man/man1 \
    && apt-get install -y --no-install-recommends \
       wget ca-certificates unzip locales procps graphviz git default-jre nodejs vim redis-tools \
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

# install nextflow
RUN mkdir /opt/nextflow && cd /opt/nextflow && wget -qO- get.nextflow.io | bash && ln -s /opt/nextflow/nextflow /usr/local/bin/nextflow

# update npm
RUN curl -L https://www.npmjs.com/install.sh | sh

# install pecl extension
RUN pecl install mongodb && docker-php-ext-enable mongodb && rm -rf /tmp/pear

# install GD
RUN docker-php-ext-configure gd --with-png-dir \
    --with-jpeg-dir --with-xpm-dir \
    --with-webp-dir --with-freetype-dir \
    && docker-php-ext-install -j$(nproc) gd

# install more php extensions
RUN docker-php-ext-install -j$(nproc) zip bcmath mysqli pdo_mysql pcntl

# install php redis extension
RUN pecl install redis && docker-php-ext-enable redis && rm -rf /tmp/pear

# set locale to utf-8
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && locale-gen
ENV LANG='en_US.UTF-8' LANGUAGE='en_US:en' LC_ALL='en_US.UTF-8'

COPY vhost.conf /etc/apache2/sites-available/000-default.conf

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && sed -i 's/post_max_size.*$/post_max_size = 2000M/g' /usr/local/etc/php/php.ini \
    && sed -i 's/upload_max_filesize.*$/upload_max_filesize = 2000M/g' /usr/local/etc/php/php.ini

COPY . /app

RUN mkdir -p /mnt/data /mnt/db && chown -R www-data:www-data /app

EXPOSE 80

CMD ["php", "/app/artisan", "horizon"]
CMD ["apache2-foreground"]
