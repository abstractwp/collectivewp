# WordPress

This is a WordPress repository configured to run on the [Pantheon platform](https://pantheon.io).

Pantheon is website platform optimized and configured to run high performance sites with an amazing developer workflow. There is built-in support for features such as Varnish, Redis, Apache Solr, New Relic, Nginx, PHP-FPM, MySQL, PhantomJS and more. 

## Getting Started

### 1. Spin-up a site

If you do not yet have a Pantheon account, you can create one for free. Once you've verified your email address, you will be able to add sites from your dashboard. Choose "WordPress" to use this distribution.

### 2. Load up the site

When the spin-up process is complete, you will be redirected to the site's dashboard. Click on the link under the site's name to access the Dev environment.

![alt](http://i.imgur.com/2wjCj9j.png?1, '')

### 3. Run the WordPress installer

How about the WordPress database config screen? No need to worry about database connection information as that is taken care of in the background. The only step that you need to complete is the site information and the installation process will be complete.

We will post more information about how this works but we recommend developers take a look at `wp-config.php` to get an understanding.

![alt](http://i.imgur.com/4EOcqYN.png, '')

If you would like to keep a separate set of configuration for local development, you can use a file called `wp-config-local.php`, which is already in our .gitignore file.

### 4. Enjoy!

![alt](http://i.imgur.com/fzIeQBP.png, '')

## Branches

The `default` branch of this repository is where PRs are merged, and has [CI](https://github.com/pantheon-systems/WordPress/tree/default/.circleci) that copies `default` to `master` after removing the CI directories. This allows customers to clone from `master` and implement their own CI without needing to worry about potential merge conflicts.

## Custom Upstreams

If you are using this repository as a starting point for a custom upstream, be sure to review the [documentation](https://pantheon.io/docs/create-custom-upstream#pull-in-core-from-pantheons-upstream) and pull the core files from the `master` branch.
# The Collective Work And Place WordPress Project on WP Engine

## Table of Contents (coming soon...)

- Features
- Directory Structure
- Set Up Local Environment
- Making & Committing Changes
- 3rd Party Documentation
- EXTERNAL [Recommended Plugins & Tools](#table-of-contents-coming-soon)

## Features

Coming soon...

## Directory Structure

```shell
PROJECT/                             # → Root Directory
├── wp-content/                      #
│   ├── themes/                      #
│   │   └── collectivewp             # → Project Theme
│   └── plugins/                     #
│       └── collectivewp-functions   # → Project Functionality
└── .gitignore                       # → WP specific gitignore
```

## Setup Your Local Environment

1. Install a fresh WordPress install locally. You can use MAMP, [LocalWP](https://localbyflywheel.com/), or other tools. We recommend one that includes [WP-CLI](https://wp-cli.org/).
2. Get the `/wp-content/` folder from the production server and replace the `wp-content/themes/` and `wp-content/plugins/` folders in your local environment. Exclude all other folders or files from the ZIP file. For the WordPress code base outside of this repository you will need a ZIP file containing plugins, themes, or other required files under `/wp-content/`. Contact your team lead or visit the hosting portal to download a backup.
3. Import the database locally. You can use phpMyAdmin, direct SQL query or [Migrate DB Pro](https://deliciousbrains.com/wp-migrate-db-pro/). Contact your team lead or visit the production WordPress admin to obtain the latest database from production.
4. Update the URLs in the local database. Using wp cli: `wp search-replace $(wp option get siteurl) http://wp.dev/mywebsite`, where the last URL is your local site’s URL. If you don’t have wp cli, try [Search Replace DB](https://interconnectit.com/products/search-and-replace-for-wordpress-databases/).
5. In you local environment, delete the theme `wp-content/themes/collectivewp/` and the plugin `wp-content/plugins/collectivewp-functions`.
6. Go to the top level of the WordPress directory in terminal and run the following commands. This will pull down the version controlled theme and plugin. *Note: If you're using LocalWP this is under `sites/<your-site-directory>/app/public/`*

```shell
git init
git remote add origin git@github.com:abstractwp/collectivewp.git
git pull origin main
git fetch --all
git checkout main
# Checkout theme
git submodule update
# Download all plugins via composer
composer install
```

## Using NPM, Composer, & WP-CLI without installation

WP Engine DevKit & LocalWP include with a variety of tools pre-installed:

- WP-CLI
- Composer
- NPM
- Gulp
- Grunt

LocalWP: In the Local by Flywheel interface right click the install name and click `open site SSH`

## Making Project Theme Changes

Navigate to the theme directory

```shell
cd wp-content/themes/collectivewp/
```
Follow README in the theme.

