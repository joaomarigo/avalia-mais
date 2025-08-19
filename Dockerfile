    # Dockerfile for PHP 8.2 + Apache on Render
    FROM php:8.2-apache

    # Install PHP extensions needed by the project
    RUN docker-php-ext-install pdo pdo_mysql mysqli

    # Enable Apache modules
    RUN a2enmod rewrite

    # Set DocumentRoot to /var/www/html/php because your index.php está em /php
    ENV APACHE_DOCUMENT_ROOT=/var/www/html/php
    RUN sed -ri -e 's!DocumentRoot /var/www/html!DocumentRoot ${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf &&         sed -ri -e 's!</VirtualHost>!
    <Directory ${APACHE_DOCUMENT_ROOT}>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>!g' /etc/apache2/sites-available/000-default.conf &&         sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

    # Copy app
    COPY . /var/www/html

    # Adjust Apache to listen on Render's $PORT (falls back to 8080 locally)
    COPY docker/start.sh /usr/local/bin/start.sh
    RUN chmod +x /usr/local/bin/start.sh

    EXPOSE 8080
    CMD ["/usr/local/bin/start.sh"]
