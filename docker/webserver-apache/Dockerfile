FROM php:8.0.1-apache-buster

# Setting up constants for an environment
ENV TIMEZONE            UTC
ENV PHP_MEMORY_LIMIT    512M

# Setting up general libraries
RUN apt-get update \
  && apt-get upgrade \
  && apt-get install -y apt-utils vim wget alien ca-certificates git make autoconf g++ gcc \
  libmemcached-dev libc-dev libzip-dev libssl-dev zlib1g-dev re2c libpcre3-dev \
  build-essential libicu-dev libxml2-dev libjpeg-dev libfreetype6-dev \
  libsodium-dev libldap2-dev libaio1 iproute2 lsb-release

# Setting up php
RUN docker-php-ext-configure intl \
  && docker-php-ext-install intl \
  && docker-php-ext-install gettext \
  && docker-php-ext-install opcache \
  && docker-php-ext-install pdo_mysql \
  && pecl install apcu \
  && docker-php-ext-enable apcu \
  && pecl install zip \
  && docker-php-ext-enable zip \
  && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug

# Install composer
RUN curl -sS http://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer

# Setting up httpd issues
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite headers && /etc/init.d/apache2 restart
RUN echo "127.0.0.1     www.my.scooter.com my.scooter.com" >> /etc/hosts

# RabbitMQ etc
#RUN apt-get update && apt-get install -y gnupg2
#RUN curl -fsSL https://github.com/rabbitmq/signing-keys/releases/download/2.0/rabbitmq-release-signing-key.asc | apt-key add -
#RUN apt-key adv --keyserver "hkps://keys.openpgp.org" --recv-keys "0x0A9AF2115F4687BD29803A206B73A36E6026DFCA"
#RUN apt-get install apt-transport-https
#RUN apt-get install -y erlang-base \
#    && apt-get update -y
#RUN erlang-asn1 erlang-crypto erlang-eldap erlang-ftp erlang-inets \
#    && erlang-mnesia erlang-os-mon erlang-parsetools erlang-public-key \
#    && erlang-runtime-tools erlang-snmp erlang-ssl \
#    && erlang-syntax-tools erlang-tftp erlang-tools erlang-xmerl
#RUN apt-get update -y
#
#RUN wget -O - "https://packagecloud.io/rabbitmq/rabbitmq-server/gpgkey" | apt-key add -
#RUN echo "deb https://dl.bintray.com/rabbitmq/debian buster main" | tee /etc/apt/sources.list.d/bintray.rabbitmq.list
#RUN apt-get update -y
#RUN apt-get install -y rabbitmq-server
#RUN service rabbitmq-server start

WORKDIR "/code"
