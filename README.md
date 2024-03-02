# Project : DBMS UI for web

## Contributors

**yeongdaekim(roby238)**
- Web Design(with css)
- SQL(with Mysql)
- HTML, PHP and JS(main / log in / generate new table)

# Clone code

* Enter below code to run this project.

```shell
git clone https://github.com/roby238/dbms-ui.git
```

## Prerequite

* I worked in PHP 8.2.15. So I recommend to work in same version for this project.

## Steps to run

1. Install php, php8.2-mysql extension and php8.2-pdo extension.

2. Set user and password in Mysql.
  * password should NOT consist of '@'. It is a delimeter in system. 

3. Make DB. And DB name must be 'db' + user.

4. Run php server with Apache2.
  * refer to given php.ini file for setting php. Modify /etc/php/8.2/apache2/php.ini.
    ```shell
    # Modify php.ini.
    cd /etc/php/8.2/apache2
    cp /home/[user]/dbms-ui/php.ini .
    ```

## Output

![dbms ui](https://github.com/roby238/dbms-ui/assets/45201672/aa07dc09-09a0-4b92-b093-33c6ff41f86a)
