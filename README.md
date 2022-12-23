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
│   │   └── collectivewp-theme       # → Project Theme
│   └── plugins/                     #
│       └── collectivewp-functions   # → Project Functionality
└── .gitignore                       # → WP specific gitignore
```

## Setup Your Local Environment

1. Install a fresh WordPress install locally. You can use MAMP, [LocalWP](https://localbyflywheel.com/), or other tools. We recommend one that includes [WP-CLI](https://wp-cli.org/).
2. Get the `/wp-content/` folder from the production server and replace the `wp-content/themes/` and `wp-content/plugins/` folders in your local environment. Exclude all other folders or files from the ZIP file. For the WordPress code base outside of this repository you will need a ZIP file containing plugins, themes, or other required files under `/wp-content/`. Contact your team lead or visit the hosting portal to download a backup.
3. Import the database locally. You can use phpMyAdmin, direct SQL query or [Migrate DB Pro](https://deliciousbrains.com/wp-migrate-db-pro/). Contact your team lead or visit the production WordPress admin to obtain the latest database from production.
4. Update the URLs in the local database. Using wp cli: `wp search-replace $(wp option get siteurl) http://wp.dev/mywebsite`, where the last URL is your local site’s URL. If you don’t have wp cli, try [Search Replace DB](https://interconnectit.com/products/search-and-replace-for-wordpress-databases/).
5. In you local environment, delete the theme `wp-content/themes/collectivewp-theme/` and the plugin `wp-content/plugins/collectivewp-functions`.
6. Go to the top level of the WordPress directory in terminal and run the following commands. This will pull down the version controlled theme and plugin. *Note: If you're using LocalWP this is under `sites/<your-site-directory>/app/public/`*

```shell
git init
git remote add origin git@github.com:abstractwp/collectivewp.git
git pull origin main
git fetch --all
git checkout main
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
cd wp-content/themes/collectivewp-theme/
```

Create a new branch for your changes ([We use Jira tickets](https://abstractwp.atlassian.net/browse/WAPL-))

```shell
git checkout -b your-branch-name
git branch --track origin your-branch-name
```

If required run NPM

```shell
npm install
npm start
```

Pushing your changes

```shell
git add .
git commit -m "your awesome commit message"
git push
```

