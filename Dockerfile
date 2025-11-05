FROM php:7.4-apache

ARG PROJECT_NAME=api_platia

RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

RUN mkdir -p /var/www/${PROJECT_NAME}
COPY . /var/www/${PROJECT_NAME}/
WORKDIR /var/www/${PROJECT_NAME}/
RUN chown -R www-data:www-data /var/www/${PROJECT_NAME}

RUN echo "<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/${PROJECT_NAME}/public\n\
    ServerName ${PROJECT_NAME}.local\n\
    <Directory /var/www/${PROJECT_NAME}/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog /var/log/apache2/${PROJECT_NAME}_error.log\n\
    CustomLog /var/log/apache2/${PROJECT_NAME}_access.log combined\n\
</VirtualHost>" > /etc/apache2/sites-available/${PROJECT_NAME}.conf

RUN a2ensite ${PROJECT_NAME}.conf && a2dissite 000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80
