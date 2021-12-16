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

- Just install extension using composer or through the extension manager
- Include the Static TypoScript ``Web2PDF Generator``
- Add the PDF generation link to your page template as suggested below

There are two ways of displaying the PDF generation link on page:

1. Insert the Plugin ``Web2PDF Generator`` as plugin via backend
2. Use ``lib.web2pdf`` in TypoScript to show link on each page (e.g. ``page.100 < lib.web2pdf``)

Configuration
-------------

* Extension can be configured in constant manager, see configuration_.
