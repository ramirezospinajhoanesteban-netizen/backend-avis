FROM php:8.4-apache

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    pkg-config \
    build-essential \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Extensiones PHP
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    bcmath \
    zip

# Apache rewrite
RUN a2enmod rewrite

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar todo el proyecto primero para evitar errores con los scripts de Laravel en composer install
COPY . .

# Instalar dependencias de PHP
RUN composer install --optimize-autoloader --no-interaction

# Frontend build removed to focus on backend API
# Permisos Laravel
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Public folder Apache
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80