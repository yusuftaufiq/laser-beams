FROM phpswoole/swoole:4.8.5-php8.1

# Install packages
RUN apt update && apt install -y inotify-tools --no-install-recommends

# Use this when in production mode
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Override default Swoole startup server file
COPY docker-configs/supervisor/swoole.conf /etc/supervisor/service.d/swoole.conf
COPY docker-configs/supervisor/swoole.conf /etc/supervisor/conf.d/swoole.conf

# Setup document root
RUN mkdir -p /var/www/html/laser-beams

# Switch to application directory
WORKDIR /var/www/html/laser-beams

# Add application
COPY . .

# Expose the port PHP built-in web server is reachable on
# EXPOSE 8888

# Starting the web server
# CMD php skeleton/bin/simps.php http:start
