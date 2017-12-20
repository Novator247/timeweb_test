# TimeWeb Test Application

Requirements
------------
```
PHP >= 7.1
mysql 5.7
````

Configuration
----------------
Для запуска приложения необходимо создать и заполнить два файла конфигурации
/application/config/main.php
```php
 [
    'db' => [
        'driver'   => 'Pdo_Mysql',
        'hostname' => 'localhost',
        'database' => 'timeweb',
        'username' => 'root',
        'password' => 'root',
        'port'      => 3306,
    ]
 ]
```
/phinx.yml - конфигурационный файл мигратора для БД
```yaml
paths:
    migrations: %%PHINX_CONFIG_DIR%%/application/migrations

environments:
    default_migration_table: phinxlog
    default_database: production
    production:
        adapter: mysql
        host: localhost
        name: timeweb
        user: root
        pass: root
        port: 3306
        charset: utf8

version_order: creation
```

Install
-------
```
composer install
```
После выполенения комманды будет запущена миграция в базу данных для создания таблиц и сформированы в webroot проекта assets