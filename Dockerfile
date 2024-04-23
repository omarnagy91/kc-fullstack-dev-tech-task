FROM php:8.3-apache

# Install PDO MySQL driver
RUN docker-php-ext-install pdo_mysql

# Other setup...