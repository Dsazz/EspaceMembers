# Space-Project on Symfony2

1. Symfony2
----------

Clone the repo

```bash
$ git@github.com:Dsazz/Space-Project.git [your-name]
```

And then install composer dependencies

```bash
$ cd [project-name]
$ composer install
```

When installer asks for parameters leave them all at default values.


2. Doctrine
-----------

After installation create the schema and load fixtures

```bash
$ app/console doctrine:schema:create
$ app/console doctrine:fixtures:load
```


3. Selenium
-----------

Download into your project selenium server link(http://www.seleniumhq.org/download/).

Run it:

```bash
$ java -jar selenium-server-standalone-[version].jar
```


4. Behat
--------

To run the tests Behat:

```bash
$ php bin/behat
```
