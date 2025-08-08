FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    unzip git libzip-dev \
  && docker-php-ext-install zip mysqli pdo pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

# ✖️ Removemos o ENV APACHE_DOCUMENT_ROOT para deixar /var/www/html como raiz
COPY . /var/www/html/
