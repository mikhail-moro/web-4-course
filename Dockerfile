FROM ubuntu:latest
WORKDIR /app
COPY . .

RUN apt-get update && apt-get install -y php-cli

CMD ["php", "example-php.php"]