# CRADLE

Cradle is a PHP microframework for architecting small and efficient microservices, built on top of [the Slim microframework](http://www.slimframework.com/) and open source components. It's intended to be used for micro service development but is powerful enough to be used to develop fullstack apps. Cradle is best used as an API with a frontend framework like VueJS or ReactJS.

Cradle features a minimal and well structured directory, it uses Laravel's Eloquent as an ORM, symfony's twig for view rendering and composer for dependency management. It requires at least PHP 7.2 to work properly.

If you appreciate the project, remember to leave a star! Thank you.

Developed and maintained by [Mohammed I. Adekunle](https://github.com/Iyiola-am).

## Requirements

- PHP 7.2+
- Apache's mod_rewrite module or similar.
- Composer.
- MySQL or Similar.

## Usage

- Clone the repository's files in your web server's root directory.
- Run `composer install`.
- Copy the `.env.example` file to `.env`.
- Update your `.env` file according to your web server's environment.
- Run `ln -sT $(pwd)/storage/public ./public/storage` to create a symbolic link.
- Build something awesome.

## License

Cradle is licensed under the [MIT license](http://opensource.org/licenses/MIT).
