.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration Reference
=======================

.. _configuration-site-settings:

Site Settings (Site Set)
------------------------

When the extension is included via its **Site Set**, all settings are available
in the TYPO3 backend under :guilabel:`Site Management > Sites > Settings`.
Changes take effect immediately without editing TypoScript.

The following settings are grouped under the **PDF Settings** category:

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field

   :Description:
         Description

   :Default:
         Default

 - :Field:
         pdfPageFormat

   :Description:
         Paper size of the generated PDF. Allowed values: ``A4``, ``A3``

   :Default:
         A4

 - :Field:
         pdfPageOrientation

   :Description:
         Page orientation. ``P`` = Portrait, ``L`` = Landscape

   :Default:
         P

 - :Field:
         pdfLeftMargin

   :Description:
         Left margin in millimetres

   :Default:
         15

 - :Field:
         pdfRightMargin

   :Description:
         Right margin in millimetres

   :Default:
         15

 - :Field:
         pdfTopMargin

   :Description:
         Top margin in millimetres

   :Default:
         15

 - :Field:
         pdfBottomMargin

   :Description:
         Bottom margin in millimetres

   :Default:
         15

 - :Field:
         pdfStyleSheet

   :Description:
         CSS media type applied when rendering the page for PDF output.
         ``allAndPrint`` includes both ``all`` and ``print`` rules.

   :Default:
         allAndPrint

 - :Field:
         pdfDestination

   :Description:
         Controls how the browser handles the PDF response.

         * ``attachment`` — triggers a file download
         * ``inline`` — opens the PDF directly in the browser

   :Default:
         attachment

 - :Field:
         useCustomHeader

   :Description:
         If enabled, renders ``Partials/Pdf/Header.html`` as a repeating
         header on every PDF page.

   :Default:
         false

 - :Field:
         useCustomFooter

   :Description:
         If enabled, renders ``Partials/Pdf/Footer.html`` as a repeating
         footer on every PDF page.

   :Default:
         false

The following settings are grouped under the **View** category:

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field

   :Description:
         Description

   :Default:
         Default

 - :Field:
         templateRootPath

   :Description:
         Path to the Fluid template directory.

   :Default:
         EXT:web2pdf/Resources/Private/Templates/

 - :Field:
         partialRootPath

   :Description:
         Path to the Fluid partials directory. Custom PDF header and footer
         templates are looked up here.

   :Default:
         EXT:web2pdf/Resources/Private/Partials/

 - :Field:
         layoutRootPath

   :Description:
         Path to the Fluid layouts directory.

   :Default:
         EXT:web2pdf/Resources/Private/Layouts/

.. _configuration-typoscript:

TypoScript Reference (Static TypoScript / advanced)
----------------------------------------------------

When using the classic Static TypoScript approach, or when you need settings
that are not exposed as Site Settings (such as string replacements), configure
the extension in ``plugin.tx_web2pdf``.

Template
~~~~~~~~

Override ``plugin.tx_web2pdf.view`` to use a custom template. The default
template is ``EXT:web2pdf/Resources/Private/Templates/Pdf/GeneratePdfLink.html``.

String replacements
~~~~~~~~~~~~~~~~~~~

Two replacement mechanisms are available and can be combined. Search and
replacement arrays must use matching numeric keys.

**Regex replacement** (``preg_replace``):

.. code-block:: typoscript

   plugin.tx_web2pdf.settings {
       pdfPregSearch {
           1 = /Hello/
       }
       pdfPregReplace {
           1 = Hi
       }
   }

**Plain string replacement** (``str_replace``):

.. code-block:: typoscript

   plugin.tx_web2pdf.settings {
       pdfStrSearch {
           1 = Hello
       }
       pdfStrReplace {
           1 = Good Night
       }
   }

.. note::

   String replacements cannot be configured via Site Settings and always
   require TypoScript.
