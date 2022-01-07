FROM phpswoole/swoole:4.8.5-php8.1

# Install packages
RUN apt update && apt install -y --no-install-recommends inotify-tools mariadb-client

# Install PDO MySQL & Opcache
RUN docker-php-ext-install mysqli pdo_mysql opcache
RUN docker-php-ext-configure opcache --enable-opcache

# Use this while in production mode
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Override default Swoole startup server file
COPY docker-configs/supervisor/swoole.conf /etc/supervisor/service.d/swoole.conf
COPY docker-configs/supervisor/swoole.conf /etc/supervisor/conf.d/swoole.conf

# Setup document root
RUN mkdir -p /var/www/html/laser-beams

# Switch to application directory
WORKDIR /var/www/html/laser-beams

# Install composer dependencies
COPY composer.json ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy custom PHP configuration
COPY docker-configs/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Add application
COPY . .

# Read and execute access for everyone and also write access for the owner of the file
RUN chmod -R 755 /var/www/html/laser-beams/docker-configs/php

# Expose the port Swoole server is reachable on
EXPOSE 3030

# Starting the web server
ENTRYPOINT [ "" ]
CMD ./docker-configs/wait-for-it.sh $MYSQL_HOST:3306 && \
    mysql -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -h "$MYSQL_HOST" -P 3306 -D "$MYSQL_DBNAME" < ./migrations/20220104105418_create_todos_activities_table.sql && \
    /entrypoint.sh
