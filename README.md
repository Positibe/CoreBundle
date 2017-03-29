PositibeCoreBundle
==================

Positibe CoreBundle provide common features to Positibe bundles.

Integrate Sylius ResourcesBundle, Gedmo DoctrineExtension and some other functions.

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
            /************* Sylius Required bundles ***************/
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
            new winzou\Bundle\StateMachineBundle\winzouStateMachineBundle(),
            /************** Stof DoctrineExtensionBundle ****************/
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

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

LuneticsLocaleBundle
--------------------

This bundle has several improvement to be used with LuneticsLocaleBundle

En Español
~~~~~~~~~~

Ver la documentación en /path/to/bundle/Resources/doc/es/index.md




