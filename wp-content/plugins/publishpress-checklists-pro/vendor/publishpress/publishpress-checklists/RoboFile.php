<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \PublishPressBuilder\PackageBuilderTasks
{
    public function __construct()
    {
        parent::__construct();

        $this->setVersionConstantName('PPCH_VERSION');
        $this->setVersionConstantFiles(['includes.php']);
        $this->appendToFileToIgnore(
            [
                '.phplint.yml',
                'webpack.config.js',
                'vendor/pimple/pimple/.gitignore',
                'vendor/pimple/pimple/.php_cs.dist',
                'vendor/psr/container/.gitignore',
                'vendor/publishpress/wordpress-version-notices/.gitignore',
                'vendor/publishpress/wordpress-version-notices/README.md',
                'vendor/publishpress/wordpress-version-notices/bin',
                'vendor/publishpress/wordpress-version-notices/codeception.dist.yml',
                'vendor/publishpress/wordpress-version-notices/codeception.yml',
                'vendor/publishpress/wordpress-version-notices/tests',
            ]
        );
    }
}
