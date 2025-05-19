# Usar una imagen oficial con PHP, Composer y extensiones
FROM webdevops/php-apache:8.2

# Copiar archivos del proyecto
COPY . /app

# Establecer el directorio de trabajo
WORKDIR /app

# Instalar dependencias
RUN composer install --no-interaction --optimize-autoloader

# Dar permisos al almacenamiento y cachés
RUN chmod -R 775 storage bootstrap/cache

# Exponer el puerto 8080 (usado por Render)
EXPOSE 8080

# Comando para iniciar Laravel
CMD php artisan serve --host=0.0.0.0 --port=8080
