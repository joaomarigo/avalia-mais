# Dockerfile for PHP 8.2 + Apache on Render
FROM php:8.2-apache

# Extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Ativa mod_rewrite
RUN a2enmod rewrite

# (A) Substitui o vhost padrão inteiro por um nosso (DocumentRoot -> /var/www/html/php)
COPY apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# (B) Habilita .htaccess para /var/www/html/php
COPY apache-app.conf /etc/apache2/conf-available/z-app.conf
RUN a2enconf z-app

# Copia o app
COPY . /var/www/html

# (C) Fallback: se alguém acessar /, redireciona para /php/
RUN /bin/sh -lc 'printf "%s\n" "<?php header(\"Location: /php/\"); exit; ?>" > /var/www/html/index.php'

# Garante prioridade para index.php
RUN sed -ri -e 's@DirectoryIndex .*@DirectoryIndex index.php index.html@' /etc/apache2/mods-enabled/dir.conf

# Start na porta $PORT do Render
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 8080
CMD ["/usr/local/bin/start.sh"]
