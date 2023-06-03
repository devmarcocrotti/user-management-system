FROM php:8.1

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_pgsql mysqli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json .

RUN composer install --no-dev --optimize-autoloader

COPY . .

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public/"]
