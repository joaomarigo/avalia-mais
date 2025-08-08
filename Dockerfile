# Dockerfile — mantém sua estrutura como está e serve tudo
FROM php:8.2-apache

# Dependências e extensões PHP
RUN apt-get update && apt-get install -y \
    unzip git libzip-dev \
  && docker-php-ext-install zip mysqli pdo pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

# Habilita mod_rewrite (URLs amigáveis / .htaccess)
RUN a2enmod rewrite

# Copia o projeto inteiro (sem mover nada)
COPY . /var/www/html/

# Permissões básicas
RUN chown -R www-data:www-data /var/www/html

# Apache:
# - mantém DocumentRoot padrão (/var/www/html)
# - libera .htaccess e acesso às pastas
# - aceita fallback para php/index.php se a raiz não tiver index
RUN printf '%s\n' \
  'DirectoryIndex index.php index.html php/index.php proj_avalia/index.php proj_avalia/php/index.php' \
  '<Directory /var/www/html>' \
  '  Options Indexes FollowSymLinks' \
  '  AllowOverride All' \
  '  Require all granted' \
  '</Directory>' \
  '<Directory /var/www/html/php>' \
  '  Options Indexes FollowSymLinks' \
  '  AllowOverride All' \
  '  Require all granted' \
  '</Directory>' \
  '<Directory /var/www/html/proj_avalia>' \
  '  Options Indexes FollowSymLinks' \
  '  AllowOverride All' \
  '  Require all granted' \
  '</Directory>' \
  '<Directory /var/www/html/proj_avalia/php>' \
  '  Options Indexes FollowSymLinks' \
  '  AllowOverride All' \
  '  Require all granted' \
  '</Directory>' \
  > /etc/apache2/conf-available/project.conf \
  && a2enconf project
