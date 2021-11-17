
<p align="center">
  <a href="https://stacksync.netlify.app/">
    <img width="790" height="157" src="https://user-images.githubusercontent.com/9354045/142086641-ab6b172e-194b-4d1e-9f0a-f1f94a42a353.png">
  </a>
</p>
<p align="center">
  <a href="LICENSE">
    <img src="https://img.shields.io/badge/license-MIT--Clause-brightgreen.svg?style=flat-square" alt="Software License"></img>
  </a>
  <a href="https://github.com/KhomsiAdam/StackSync/releases">
    <img src="https://img.shields.io/github/release/KhomsiAdam/StackSync.svg?style=flat-square" alt="Latest Version"></img>
  </a>
</p>
  
# Introduction

[StackSync](https://stacksync.netlify.app/) is a simple, lightweight and native fullstack PHP mini-framework.

It is not a fully fledged framework and does not have all the complex features and the level of polish of other frameworks that are built by communities of professionals, therefore it is not suitable for big commercial projects or for other compagnies to use.

But it can still create powerful applications, and is more destined to be used by beginners and other developpers for personal projects.

Designed to be easy of use while having a strong foundation of the web stack of PHP, MySQL, HTML, JavaScript and CSS (or SASS/SCSS).

Requires basic knowledge of APIs, JSON, Object Oriented Programming.

The code is heavily commented and provides some examples and explanations, for a more detailed rundown, make sure to read through the [documentation](https://stacksync.netlify.app/) that covers every aspect of the mini-framework and get started.

# Table of Contents
<!--ts-->
* [Setup](#setup)
  * [Installation](#installation)
  * [Configuration](#configuration)
* [Directory Structure](#directory-structure)
* [Advanced](#advanced)
* [Commands](#commands)
* [Contributions](#contributions)
<!--te-->

# [Setup](https://stacksync.netlify.app/setup.html)

## [Installation](https://stacksync.netlify.app/setup.html#install)

[Composer](https://getcomposer.org/) is required.

StackSync is available as a composer package, you can create a new StackSync project by running the command below in your terminal while defining the targeted folder:

```bash
composer create-project khomsiadam/stacksync project-name

```

Alternatively, you can download or clone the repository, then open the project and run the following command in your terminal:

```bash
composer install

```

This installs the dependencies for [php-jwt](https://github.com/firebase/php-jwt) and [phpdotenv](https://github.com/vlucas/phpdotenv) which are the only required packages.

[Back to top](#table-of-contents)

## [Configuration](https://stacksync.netlify.app/setup.html#config)

After creating your database, in your root directory, you will find a `.env.example` file. Copy and rename to `.env` and start filling in your database informations:

```
# Database Credentials: *fill with your proper database information
DB_HOST=
DB_NAME=
DB_USER=
DB_PASS=

# Data Source Name: *do not modify! $_ENV['DSN'] is used as first argument for your PDO connection
DSN=mysql:host=${DB_HOST};dbname=${DB_NAME}

# Errors and responses status for error handling for the API:
ERRORS=ON
RESPONSES=ON

# Database User Table:
USER_TABLE=user

# Database User Columns (add any needed variable for every column added in user table):
USER_ID=user_id
USER_EMAIL=email
USER_PASSWORD=password

# Database Audience Table: *The recipient table for account for 'aud' in JWT token payload
AUDIENCE_TABLE=
# Database Other Tables: *Other tables you may need, as argument for your CRUD operations in controller methods
EXAMPLE_TABLE=

# API Token Authentication Secret Keys: *fill with your own key or add new keys as needed
SECRET_KEY=
```

Opening your terminal, navigating to your project folder:

```bash
cd project-name
```

Then running this command:

```bash
composer serve:local
```

Will run PHP's built in development server on your local machine and browsing to `http://localhost:8080` will show a welcome homepage.

[Back to top](#table-of-contents)

# [Directory Structure](https://stacksync.netlify.app/directories.html)

```
app/                            # The app folder is your backend folder with the main structure
|
|– config/                      # Folder containing your database, constants configuration along with the Middleware
|
|– controllers/                 # Folder containing all your controllers
|
|– core/                        # Contains the core of the mini-framework: Router, Component View system, File generation
|   |
|   |– templates/               # Folder that contains templates for file creation
|
|– migrations/                  # Any generated migration file will be here
|
|– models/                      # Folder containing all your models
|
|– seeders/                     # Any generated seeder file will be here
|
|– views/                       # Contains all files that forms a view. The content files are in the root of this folder
|   |
|   |– components/              # For view components (dynamic/static)
|   |   |
|   |   |- dynamic              # Dynamic components: part of the content
|   |   |
|   |   |- static               # Static components: part of the layout
|   |
|   |– layouts/                 # Layouts for views containing static components and the content
|
|– Application.php              # The main file that runs the application 
|
public/                         # The public folder is your frontend folder where all your assets are with the index.php file
|
|– css/                         # Folder to contain stylesheets
|
|– fonts/                       # Folder for hosting your fonts to be used with @font-face rule
|
|– icons/                       # Contains your icons
|
|– images/                      # Contains your images
|
|– js/                          # Folder for all your javascript files, libraries, modules
|
|– scss/                        # Folder to contain your SASS/SCSS files or the architecture included if used
|
|– index.php                    # Where the requests are redirected to. Routes are defined here.
|
vendor/                         # Packages folder
|
.env                            # Environement variables
|
composer.json                   # Project properties, meta data and dependencies
```

For a more detailed view on the directory structure be sure to check it's [section](https://stacksync.netlify.app/directories.html) in the documentation as it also provides some basic insights.

[Back to top](#table-of-contents)

# [Advanced](https://stacksync.netlify.app/advanced.html)

This section dives deepers and covers all the important aspects and features.

[API](https://stacksync.netlify.app/advanced.html#api): The mini-framework's custom API system and the Middleware.

[Controllers & Models](https://stacksync.netlify.app/advanced.html#controllers): How controllers and models are setup.

[Routing](https://stacksync.netlify.app/advanced.html#routing): Routing configuration for both Web routes and API endpoints.

[Views](https://stacksync.netlify.app/advanced.html#views): Views are managed with a Components Views System.

[Migrations](https://stacksync.netlify.app/advanced.html#migrations): A simple migrations system.

[Seeding](https://stacksync.netlify.app/advanced.html#seeding): Seed pre-defined data to your tables.

[Back to top](#table-of-contents)

# [Commands](https://stacksync.netlify.app/commands.html)

With the help of [composer](https://getcomposer.org/), there are a multiple of useful custom commands to execute in your terminal.

- Run PHP's own development server on your local machine (`localhost:8080`):

```bash
composer serve:local
```

- Run PHP's own development server on your local network. The address is your local IP address in your network and it can accessed by any device in the same network (ex: `192.168.1.2:8080`):

```bash
composer serve:remote
```

*The `:8080` is the default port but it can be modified in `composer.json`.

- Apply the current migrations in `app/migrations` folder in the defined sequencing order:

```bash
composer migrate:apply
```

- Drop the current applied migrations in your database. Only tables applied through the migrations system will be dropped. Any table created normally won't be affected:

```bash
composer migrate:drop
```

- Run all seeders defined in `app/seeders/DatabaseSeeder.php`:

```bash
composer seed:all
```

- Run the `app/seeders/UserSeeder.php` for seeding the `user` table:

```bash
composer seed:user
```

*Any created seeder can be added in `app/seeders/DatabaseSeeder.php` and/or have a custom script added for it under the `scripts` section in `composer.json` to be run individually.

- Alternatively, you can apply current migrations then run all seeders defined:

```bash
composer migrate:apply:seed
```

- Create a migration file while asking for it's order (that defines it's prefix and id), type (create or update), table name, auto-incremented or generated id, foreign key references and constraints:

```bash
composer make:migration
```

- Create a seeder file while asking for the targeted table name, is the id auto-incremented or generated, import of column names as keys with empty values to be filled:

```bash
composer make:seeder
```

- Creates both a controller and a model since they rely on each other while asking for the targeted table name, is the id auto-incremented or generated. Everything is setup automatically based on the tables columns and basic CRUD methods are defined by default for a quick start and as a base for custom methods:

```bash
composer make:controller
```

- Enables / disables error handling:

```bash
composer errors:on
```
```bash
composer errors:off
```

- Enables / disables responses from the API:

```bash
composer responses:on
```
```bash
composer responses:off
```

[Back to top](#table-of-contents)

# Contributions

Contributions are welcome. To discuss any bugs, problems, fixes or improvements please refer to the discussions section.

Before creating a pull request, make sure to open an issue first.

Committing your changes, fixes or improvements in a new branch with documentation will be appreciated.

[Back to top](#table-of-contents)