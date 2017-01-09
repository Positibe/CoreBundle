PositibeCoreBundle
==================

Positibe CoreBundle provides common features for Positibe bundles.

Integrate Sylius ResourcesBundle, Gedmo DoctrineExtension, Lunetics LocaleBundle and some other functions.

Installation
------------

To install the bundle just add the dependent bundles:

    php composer.phar require positibe/core-bundle

Next, be sure to enable the bundles in your application kernel:

    <?php
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            // All dependencies of all Positibe bundles
            new Positibe\Bundle\CoreBundle\PositibeCoreBundle(),

            // ...
        );
    }

Importing some Twig Extensions
------------------------------

Switch current locale
---------------------



Twig functions
--------------

**positibe_locale_switcher:**

**go_back:**

**loggable:**


Twig tests
----------

**isDate:**



En Español
~~~~~~~~~~

Ver la documentación en /path/to/bundle/Resources/doc/es/index.md




