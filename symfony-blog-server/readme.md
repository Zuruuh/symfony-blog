# Symfony Blog

## Installation

### Requirements

 - Docker
 - Docker-compose
 - OpenSSL

### Setup

```bash
composer install
docker-compose -f ./docker-compose.dev.yaml up -d # Starts all docker containers
openssl genrsa -out ./config/jwt/private.pem 4096 # Generates the jwt secret key for token verification
openssl rsa -in ./config/jwt/private.pem -out ./config/jwt/public.pem -pubout -outform PEM # Generates the jwt public key signing
```