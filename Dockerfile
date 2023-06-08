FROM php:apache

RUN chown -R www-data:www-data /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    libpq-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    libonig-dev \
    && docker-php-ext-install mbstring

RUN docker-php-ext-install zip

RUN a2enmod rewrite

COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
