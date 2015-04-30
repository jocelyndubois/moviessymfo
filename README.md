Movies Symfony
========================

Bonjour et bienvenue dans l'explication de la mise en place du projet. Veuillez suivre les instruction et pré-requis pour avoir accès au projet.

1) Installation standard
----------------------------------

>Tous d'abord vous devez créer les bases de données :

>Il vous faut 3 bases :
>* ma_base (prod)
>* ma_base_dev (dev)
>* ma_base_test (test)

>Maintenant vous devez utiliser composer. Pour cela veuillez le télécharger :

```shell
    php -r "readfile('https://getcomposer.org/installer');" | php 
```

>Puis installer le projet :

```shell
    php composer.phar update
```

>Vous suivez alors l'installation et ce qui vous est demandé

***

### Dans une utilisation du projet avec linux
>Vous devez modifier les droits des fichiers suivants :

```shell
    HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
    sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
```

***

### Maintenant il faut installer les assets :

```shell
    php app/console assets:install --symlink
    php app/console assetic:dump
```

>Puis la base de données

```shell
    php app/console doctrine:schema:update --force
    php app/console doctrine:schema:update --force --env=prod
```

>Enfin, il faut vider les caches

```shell
    php app/console cache:clear --env=prod
    php app/console cache:clear --env=dev
```

L'application est prête à être utilisée

**********

## API The Movie DB

Utilisez la clef api ci dessous

```shell
?api_key=59993a697fab87df40343a36407af05f
```