# syntax=docker/dockerfile:1
FROM php:8.4.5-apache

# Install PHP extensions required by Symfony
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure Apache for Symfony
RUN a2enmod rewrite
COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Use production configuration for PHP
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Set memory limit for Composer
ENV COMPOSER_MEMORY_LIMIT=-1

# Copy the rest of the application
COPY . .

# Create var directory and set permissions
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var/

# Expose port 80
EXPOSE 80