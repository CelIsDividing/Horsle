FROM php:8.2-apache

# PostgreSQL PDO ekstenzija (potrebna za konekciju na PostgreSQL)
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Kopiraj aplikaciju u Apache document root
COPY . /var/www/html/

# Render prosledjuje PORT env varijablu (podrazumevano 10000)
ENV PORT=10000

# Prilagodi Apache da slusa na $PORT i pokreni server
CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT}/g\" /etc/apache2/ports.conf && sed -i \"s/:80>/:${PORT}>/g\" /etc/apache2/sites-available/000-default.conf && apache2-foreground"]
