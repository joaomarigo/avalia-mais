# PHP 8.2 + Apache para Render
FROM php:8.2-apache

# Instalar dependências e extensões PHP
RUN apt-get update && apt-get install -y \
    unzip git libzip-dev \
  && docker-php-ext-install zip mysqli pdo pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

# Ativar mod_rewrite no Apache
RUN a2enmod rewrite

# Definir DocumentRoot para a pasta /php do projeto
ENV APACHE_DOCUMENT_ROOT=/var/www/html/php
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf \
    && sed -ri -e 's!<Directory /var/www/>!<Directory ${APACHE_DOCUMENT_ROOT}/>!g' /etc/apache2/apache2.conf

# Copiar o projeto para o container
COPY . /var/www/html/
