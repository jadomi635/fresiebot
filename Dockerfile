FROM webdevops/php-apache:8.2

# Copiar todo el proyecto
COPY . /app

# Establecer el directorio de trabajo
WORKDIR /app

# Instalar dependencias PHP
RUN composer install --no-interaction --optimize-autoloader

# Dar permisos a Laravel
RUN chmod -R 775 storage bootstrap/cache

# Establecer el DocumentRoot en /app/public
ENV WEB_DOCUMENT_ROOT=/app/public

# Exponer puerto 8080
EXPOSE 8080
