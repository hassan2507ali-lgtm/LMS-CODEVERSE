# Gunakan PHP 8.2 FPM
FROM php:8.2-fpm

# Install ekstensi sistem yang dibutuhkan Laravel & SQLite (untuk fitur Practice)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev

# Bersihkan cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP (MySQL untuk database utama, SQLite untuk Practice)
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Copy Composer dari image resminya
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set folder kerja kita di dalam server
WORKDIR /var/www

# Copy seluruh file project kamu ke dalam server Docker
COPY . .

# Beri izin agar folder storage bisa di-upload file SQLite oleh Admin
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache