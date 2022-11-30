FROM fsilva/php:8.1-apache

ARG ENABLE_XDEBUG_FOR_MAC=no
ARG XDEBUG_HOST=0.0.0.0
ENV UPDATE_DOCTRINE=no

RUN apt-install libcurl4-openssl-dev pkg-config libssl-dev libyaml-dev

ENV UPDATE_DOCTRINE=yes

# Install yaml
RUN pecl install yaml \
    && echo "extension=yaml.so" > /usr/local/etc/php/conf.d/yaml.ini


# Install redis
RUN pecl install -o -f igbinary apcu \
    # compile Redis with igbinary support
    &&  pecl bundle redis && cd redis && phpize && ./configure --enable-redis-igbinary && make && make install \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis igbinary apcu

# Add init scripts
COPY init.d /docker-entrypoint-init.d/
COPY etc /usr/local/etc

WORKDIR /var/www/app
