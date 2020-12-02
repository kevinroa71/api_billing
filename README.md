# Api Billing

## Instalacion de dependencias
```
composer install
```
### Configurar las llaves SSL para el token JWT
```
mkdir -p config/jwt
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
Mas informacion ver: [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#generate-the-ssl-keys)

### Crear el archivo de entorno local y configurar las siguientes variables
> project-dir/.env.local
```
APP_SECRET=cualquiercosa
DATABASE_URL="mysql://user:password@127.0.0.1:3306/billing?serverVersion=5.7"
MAILER_DSN=smtp://USER:PASS@domain:port
MAILER_SENDER_EMAIL=noreply@domain.com
JWT_PASSPHRASE=clave secreta de la llave ssl
```

### Configurar la Base de Datos
```
php project-dir/bin/console doctrine:database:create
php project-dir/bin/console doctrine:migrations:migrate
```

### Configurar el servidor
Ver [Configuracion de Referencia](https://symfony.com/doc/current/setup/web_server_configuration.html).

### Documentacion de la Api bajo la ruta
`https://api.domain.com/docs`

![documentacion](https://i.imgur.com/NRLCdQS.png)