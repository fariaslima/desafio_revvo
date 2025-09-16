# Usando a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Instala extensões do PHP necessárias (ajuste conforme necessário)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar o módulo de reescrita do Apache
RUN a2enmod rewrite

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos PHP do seu diretório local para o contêiner
COPY . /var/www/html/

# Altera o DocumentRoot para public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/public>|g' /etc/apache2/apache2.conf

# Expor a porta 80 para acesso à aplicação
EXPOSE 80