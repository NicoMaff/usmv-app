php bin/console d:m:m --no-interaction
php bin/console cache:clear --no-warmup --env=prod
exec apache2-foreground
