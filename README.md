# 4IT445 - EET (Tým 7)


## Požadavky

- Webserver (Apache, NGINX, ...)
- PHP (>= 5.4)
- Databáze (MySQL, Percona, MariaDB)


## Instalace

1) Nainstalovat composer:

```bash
php -r "readfile('https://getcomposer.org/installer');" | php
```

2) Nainstalovat závislosti:

``` bash
php composer.phar install
```

3) Naimportovat databázi (se správným uživatelem a heslem):

```sql
mysql -u root --password=abc < migrations/dump.sql
```

4) Změnit nastavení databáze v `application/configs/application.ini`.


5) Profit!
