FROM php:8.0-cli

# Debian 11 (bullseye) ya es historico: sus repos pasaron a archive.debian.org
RUN find /etc/apt -type f \( -name '*.list' -o -name '*.sources' \) -exec sed -i \
      -e 's|http://deb.debian.org/debian-security|http://archive.debian.org/debian-security|g' \
      -e 's|http://security.debian.org/debian-security|http://archive.debian.org/debian-security|g' \
      -e 's|http://deb.debian.org/debian|http://archive.debian.org/debian|g' \
      -e '/bullseye-updates/d' {} + \
    && apt-get -o Acquire::Check-Valid-Until=false update \
    && apt-get install -y --no-install-recommends \
      git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mysqli gd zip exif bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_HOME=/tmp/composer
WORKDIR /app
