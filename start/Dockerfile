FROM ubuntu:20.04

ARG TIMEZONE

RUN \
    apt-get update &&\
    apt-get upgrade -y &&\
    apt-get install -y nginx curl

RUN apt-get -y install software-properties-common

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone
# RUN printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini
RUN "date"


# php
RUN apt-get install -y language-pack-en-base
RUN LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update && apt-get install -y alien
RUN apt-get update
RUN apt-get install -y php7.4 php7.4-fpm php7.4-cli php7.4-common php7.4-json php7.4-opcache php7.4-mysql php7.4-phpdbg php7.4-mbstring php7.4-gd php7.4-imap php7.4-ldap php7.4-pgsql php7.4-pspell php7.4-soap php7.4-tidy php7.4-dev php7.4-intl php7.4-curl php7.4-zip php7.4-xml php-xdebug php7.4-bcmath

# Composer
# RUN \
#     curl -sS https://getcomposer.org/installer | php && \
#     mv composer.phar /usr/local/bin/composer && \
#     composer self-update
COPY config/composer /usr/local/bin

COPY config/default /etc/nginx/sites-available
RUN rm -rf /etc/nginx/sites-enabled/default
RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

WORKDIR /var/www/html

# RUN service nginx start
COPY start.sh /start.sh
RUN chmod +x /start.sh
CMD [ "/start.sh" ]

EXPOSE 80
