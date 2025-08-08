# Dockerfile — mantém sua estrutura como está
FROM php:8.2-apache

# Dependências e extensões PHP
RUN apt-get update && apt-get install -y \
    unzip git libzip-dev \
  && docker-php-ext-install zip mysqli pdo pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

# Habilita mod_rewrite
RUN a2enmod rewrite

# Copia seu projeto (sem mudar estrutura)
COPY . /var/www/html/

# Permissões básicas
RUN chown -R www-data:www-data /var/www/html

# Configuração do Apache para:
# - manter DocumentRoot padrão (/var/www/html)
# - aceitar .htaccess
# - permitir acesso às pastas
# - usar php/index.php como fallback se não existir index na raiz
RUN printf '%s\n' \
  'DirectoryIndex index.php index.html php/index.php' \
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
  > /etc/apache2/conf-available/project.conf \
  && a2enconf project
