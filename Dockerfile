FROM php:8.2-apache
RUN apt-get update && apt-get install -y --no-install-recommends libcurl4-openssl-dev libonig-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev util-linux \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mysqli mbstring curl gd zip \
    && php -r 'exit(extension_loaded("sodium") ? 0 : 1);' \
    && a2enmod rewrite headers expires \
    && rm -rf /var/lib/apt/lists/*
COPY . /var/www/html/
RUN find /var/www/html -type f -name '*.php' -print0 | xargs -0 -n1 php -l >/dev/null
COPY docker/entrypoint.sh /usr/local/bin/izinhukum-entrypoint
RUN chmod +x /usr/local/bin/izinhukum-entrypoint \
    && mkdir -p /var/www/html/assets/uploads /var/www/html/storage/downloads /var/www/html/storage/ticket-attachments /var/www/html/storage/update-tmp /var/www/html/backups /data \
    && chown -R www-data:www-data /var/www/html /data \
    && printf '%s\n' '<Directory /var/www/html>' 'AllowOverride All' 'Require all granted' '</Directory>' > /etc/apache2/conf-available/izinhukum.conf \
    && a2enconf izinhukum
WORKDIR /var/www/html
EXPOSE 80
ENTRYPOINT ["izinhukum-entrypoint"]
CMD ["apache2-foreground"]
