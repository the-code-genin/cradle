# CRADLE

Cradle is a PHP MVC microframework built on top of [the Slim microframework](http://www.slimframework.com/).

Cradle features a well structured directory and uses composer for dependency management. It requires at least PHP 7.2 to work properly. It is built around [the Slim microframework](http://www.slimframework.com/) and provides an MVC architecture.

You should get familiary with [the Slim microframework](http://www.slimframework.com/) first before trying out cradle as most Cradle components are built on it's components.

If you appreciate the project, remember to leave a star, thank you.

Developed and maintained by [Mohammed I. Adekunle](https://github.com/Iyiola-am).

## Installation

- Download (or clone the repository) and place the files in your web server's root directory.
- Configure your web server to direct requests to the **public** folder if the applicable asset file or directory being requested exists in the folder.
- Else, redirect all other requests to the **index.php** file in the root directory (A sample configuration for apache server has already been provided by default).
- Run "composer install".
- Build something awesome.

## Requirements

- PHP 7.2+
- Apache's mod_rewrite module or similar.
- Composer.

## Usage

### Directories

Cradle features a simple directory structure;

- **app:** This is where an app's controller, model, middleware and view files are stored.
- **framework:** This directory contains custom app components that are specific to the app but aren't third-party dependencies, e.g extensions of default cradle components.
- **public:** This is where all publicly available files are stored, the favicon.ico, robots.txt, javascript files and so on are located here.
- **resources:** This is where you app's resources (view files, routes e.t.c) are stored. The files in this directory typical don't change throughout the lifetime of your app.
- **storage:** This directory contains all the files that are not meant to be accessible to the public by default e.g font files, system logs e.t.c For Twelve factor apps, you should consider using an external service for storing your files.
- **vendor:** This directory contains the project's third-party dependencies. It is generated by composer.

### Configurations

Cradle uses environmental variables to store configuration files, by default a .env.example file is present in the root directory, you need to copy and rename this to .env, then fill in the necessary details. These variables will be loaded into the app at bootstrap time and can be used any where in your code by calling the `getenv()` function or as a key in the global `$_ENV` variable.

### MVC

Cradle follows the MVC software architecture, it uses controllers for handling requests, models for interacting with the database and views for displaying the result of a controller operation to the client. Base Views and Controllers have been perbuilt in cradle, the model implementaion is up to you, fancy an ORM? Query builder? Simple PDO? Or something else?
The choice of implementation is up to you.

#### Controllers

Controllers are classes that respond to user requests i.e user requests a web page, submits a form e.t.c. Controllers extend the [Cradle\Controller](framework/Controller.php) class or one of it's subclasses.

Controllers are stored in the **app/Controllers** directory. You may use subdirectories to organize your controllers, but you will need to specify their namespace when routing.

#### Models

The choice of implementation is up to you, but your model files are expected to be located in the **app\Models** directory.

#### Views

Views are used to display information. The information is usually supplied by a controller after it has processed a request. Ideally, views should only render information and shouldn't do any interactions with the database. You return views to be rendered from a cradle controller. Twig is the default templating engine used by cradle.

Views files are stored in the **resources/views** directory. You may extend the default View Compiler instance in order to use a templating engine apart from twig, but you will also need to extend the default [View Compiler](framework/ViewCompiler.php) class to implemention this.

### Middleware

Cradle being built on [the Slim microframework](http://www.slimframework.com/) also supports middlewares, middlewares are located in **app/Middleware** directory. A sample before middleware has been provided in the code.

### Routing

Cradle uses the default router used in [the Slim microframework](http://www.slimframework.com/).

## License

Cradle is licensed under the [MIT license](http://opensource.org/licenses/MIT).
