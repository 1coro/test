FROM ubuntu:18.04

RUN \
  apt-get update && \
  apt-get -y upgrade && \
  apt-get install -y --no-install-recommends apt-utils && \
  apt-get install -y build-essential && \
  apt-get install -y software-properties-common && \
  DEBIAN_FRONTEND=noninteractive apt-get install -y tzdata && \
  add-apt-repository ppa:ondrej/php && \
  apt-get update && \
  apt-get install -y unzip php7.2-cli php7.2-zip php7.2-mbstring php-redis && \
  rm -rf /var/lib/apt/lists/*

ENV HOME /app

COPY . /app

WORKDIR /app

RUN \
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
  php composer-setup.php --filename=composer && \
  ./composer install

CMD bash
