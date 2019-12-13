# GOG recruitment task

## Getting started

* Install php7.4 with needed extensions (you will learn which ones you are missing when trying to type `composer install`.
* Install composer
* Install symfony
* Run `composer install`
* Run `bin/console d:d:c`
* Run `bin/console d:m:m -n`
* There are fixtures so run `bin/console d:f:l -n`
* Run `symfony serve`
* Go to `localhost:8000/api`
* Run `bin/phpunit` to run tests.

## Rationale behind the solution

I thought User model was not necessary according to the task requirements. But near the end I read `User should not be
 able to add more than 3 products to the cart`, so I figured I might just in case as well include User model for 
 convenience.  
 
 I solved the task using Symfony 5 which was released on November 2019 which also means there is no behat bundle yet.
 I used PHPUnit functional tests instead (although behat would be my preferred tool).
 
 I believe the less code the better and more maintainable. That's why I chose PHP based solution - 
 Api-platform with Symfony.
  

