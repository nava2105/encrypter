# Usar la imagen oficial de PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Establecer el ServerName para suprimir la advertencia
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copiar la configuración de Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código de la aplicación al contenedor
COPY . .

# Establecer permisos (opcional, ajustar según sea necesario)
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80