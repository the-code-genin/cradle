# Cradle

Cradle is a light weight MVC framework for building web apps with PHP.

It is made with the aim to help php developers avoid working with spaghetti code and embrace the MVC software architecture in as little time as possible. It is totally free to use and open source.

Cradle features a well structured directory, an easy to use CLI tool and uses composer for dependency management. It requires at least PHP 7.2 to work properly.

Developed and maintained by [Mohammed I. Adekunle](https://github.com/Iyiola-am).

## Installation

- Download (clone the repo) and place the files in your web server's root directory
- Configure the web server to direct all requests to the **public** folder if the applicable file or directory exists in the folder
- Redirect all other requests to the **public/index.php** file (A sample configuration for apache server has already been provided by default)

## Requirements

- PHP 7.2+
- Apache's mod_rewrite module or similar
- Composer

## How To Use

Here is a simple demonstration of a web request handled using cradle MVC framework

```php

```

### Directories

Cradle features a simple directory structure

- **app**
- **config**
- **public**
- **storage**
- **vendor**

### Configurations

Configuration files are stored in the **config** directory

### MVC

Cradle follows the MVC software architecture, it uses controllers for handling web requests, models provides a layer of abstraction for interacting with the database and view

#### Controllers

Controller class files are stored in

#### Models

Model class files are stored in

#### Views

Views files are stored in

### Routing

### CLI tool

## License

Licensed under the [MIT license](http://opensource.org/licenses/MIT).