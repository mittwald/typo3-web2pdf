.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator Manual
====================

.. _admin-installation:

Installation
------------

Install the extension via Composer::

   composer require mittwald/web2pdf

.. _admin-typoscript:

Including TypoScript
--------------------

There are two ways to include the required TypoScript. Use whichever fits your
setup.

.. _admin-site-set:

Option 1: Site Set (recommended, TYPO3 ≥ 13.1)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Add the ``mittwald/web2pdf`` set to your site configuration in the TYPO3
backend under :guilabel:`Site Management > Sites`. Select your site, open the
:guilabel:`Sets` tab and add **Web2PDF Generator**.

TypoScript and all default settings are included automatically — no template
record configuration required.

.. _admin-static-typoscript:

Option 2: Static TypoScript (classic approach)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

1. Open the TYPO3 backend module :guilabel:`Web > Template`.
2. Edit the root template of your site.
3. Switch to :guilabel:`Includes` and add **Web2PDF Generator** from the list
   of available static templates.

.. _admin-plugin:

Displaying the PDF link
-----------------------

There are two ways to render the PDF generation link on a page:

1. Insert the plugin **Web2PDF Generator** on a specific page via the TYPO3
   backend (content element).
2. Include ``lib.web2pdf`` globally in your TypoScript to show the link on
   every page::

      page.100 < lib.web2pdf

.. _admin-configuration:

Configuration
-------------

* When using a **Site Set**, configure all settings under
  :guilabel:`Site Management > Sites > Settings` — see configuration_.
* When using **Static TypoScript**, configure the extension via the
  Constant Editor in :guilabel:`Web > Template` — see configuration_.
