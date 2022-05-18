FROM php:7.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY . /var/www/html/project

# Arguments defined in docker-compose.yml
ARG user=ramin
ARG uid=1000

RUN useradd -G www-data,root -u $uid -d /home/$user $user

# Set working directory
WORKDIR /var/www/html/project

RUN chown -R www-data:www-data ./

USER $user

CMD ["php", "artisan", "serve", "--port=8080"]

EXPOSE 8080