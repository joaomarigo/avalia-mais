# Dockerfile for PHP 8.2 + Apache on Render
FROM php:8.2-apache

# Instala extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Ativa mod_rewrite
RUN a2enmod rewrite

# Define o DocumentRoot para /var/www/html/php
ENV APACHE_DOCUMENT_ROOT=/var/www/html/php

# Ajusta as configs do Apache para usar a pasta php/ e garantir index.php
RUN sed -ri -e "s!DocumentRoot /var/www/html!DocumentRoot ${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/000-default.conf \
 && sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf \
 && sed -ri -e 's@DirectoryIndex .*@DirectoryIndex index.php index.html@' /etc/apache2/mods-enabled/dir.conf

# Copia conf extra para permitir .htaccess e habilita
COPY apache-app.conf /etc/apache2/conf-available/z-app.conf
RUN a2enconf z-app

# Copia o código do app
COPY . /var/www/html

# Script de start para usar a porta $PORT do Render
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 8080
CMD ["/usr/local/bin/start.sh"]
