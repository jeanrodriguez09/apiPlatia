FROM php:7.4-apache

# Directorio de trabajo
WORKDIR /var/www/html

# Copiamos el contenido del proyecto dentro del contenedor
COPY . /var/www/html/

# Activar módulos de PHP necesarios para la mayoría de apps en XAMPP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activar mod_rewrite de Apache (muchos proyectos lo usan)
RUN a2enmod rewrite

# Configurar Apache para permitir .htaccess (comportamiento típico de XAMPP)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

EXPOSE 80
