# CRADLE

Cradle is a light weight MVC framework for building web apps with PHP.

It is a skeleton of a framework and can serve as a base framework atop which a more complex framework can be built, tailored to your project's specifications. It is made with the aim to help php developers avoid working with spaghetti code and embrace the MVC software development architecture in as little time as possible. It is totally free to use and open source.

Cradle features a well structured directory and uses composer for dependency management. It requires at least PHP 7.2 to work properly.

If you appreciate the project, remember to leave a star, thank you.

Made with all the love in the world.

Developed and maintained by [Mohammed I. Adekunle](https://github.com/Iyiola-am).


## Installation

- Download (clone the repo) and place the files in your web server's root directory
- Configure your web server to direct all requests to the **public** folder if the applicable file or directory being requested exists in the folder
- Redirect all other requests to the **public/index.php** file (A sample configuration for apache server has already been provided by default)


## Requirements

- PHP 7.2+
- Apache's mod_rewrite module or similar
- Composer


## Usage

### Directories

Cradle features a simple directory structure

- **app:** This is where an app's controllers, models and view files are stored.
- **config:** This directory contains all of an app's configuration files, e.g the database and email configurations.
- **core:** This directory contains custom app components that are specific to the app but aren't third-party dependencies, e.g extensions of default cradle components. 
- **public:** This is where all publicly available files are stored, the favicon.ico, robots.txt, javascript files and so on are located here.
- **storage:** This directory contains all the files that are not meant to be accessible to the public by default e.g font files, system logs e.t.c
- **vendor:** This directory contains the project's third-party dependencies.

### Configurations

Configuration files are stored in the **config** directory, all configuration files use the namespace **App/Config**. They typically contain a constant whose value is an array, a sample configuration file would look like this:

```php
namespace App\Config;

/**
 * A sample configuration
 */
const SAMPLE = [
	'hello' => 'world',
	'foo' => 'bar'
];
```

This file would be saved as **sample.php** in the **config** directory.

To use the configuration file, you would first need to include it in whatever file you plan to use it i.e

```php
use const App\Config\SAMPLE;
```

### MVC

Cradle follows the MVC software architecture, it uses controllers for handling requests, models for interacting with the database and views for displaying the result of a controller operation to the client.

#### Controllers

Controllers are classes that respond to user actions i.e user requests a web page, submits a form e.t.c. Controllers extend the [Cradle\Components\Controller](vendor/cradle/Components/Controller.php) class or one of it's subclasses.

Controllers are stored in the **app/controllers** directory. You may use subdirectories to organize your controllers, but you will need to specify their namespace when routing.

#### Models

Models are classes that interact with the database, they are to be used by controllers to fetch, insert, update or delete data in your database. Models extend the [Cradle\Components\Model](vendor/cradle/Components/Model.php) class or one of it's subclasses, although this is not compulsory. By default, at the beginning of any project, you are expected to extend and create a default Model class and store it in the **core** directory, this custom component will then be extended by all other models in your project to ensure uniformity.

Models are stored in the **app/models** directory. You may use subdirectories to organize your models, but you will need to specify their namespace when using it in a controller class.

Ideally, all interactions with the database should only occur in a model as this conforms with the MVC paradigm.

#### Views

Views are used to display information. The information is usually supplied by a controller after it has processed a request. Ideally, views should only render information and shouldn't do any interactions with the database. You load views to be rendered in a controller, like below:

```php
$this->loadView('home');
```

This loads the view file **app/views/home.php** into the controller's ViewCompiler instance to be rendered after the controller's operation. Views are rendered in the order in which they are loaded by the controller.

Views files are stored in the **app/views** directory. You may extend the default ViewCompiler instance in order to use a templating engine like twig and the likes, but you will also need to extend the default controller class to rectify this change. All extensions should be implemented by project components and should be stored in the **core** directory.

### Routing

The cradle routing process maps request URIs to an applicable controller, the routing rules are defined in the **app/config/routes.php** file.


## License

Cradle is licensed under the [MIT license](http://opensource.org/licenses/MIT). Feel free to use