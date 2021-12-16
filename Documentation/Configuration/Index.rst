.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration Reference
=======================

.. _configuration-typoscript:

Template
--------
Override ``plugin.tx_web2pdf.view`` to use you own template. Default template is found in
``EXT:web2pdf/Resources/Private/Templates/Pdf/GeneratePdfLink.html``

TypoScript Reference
--------------------

* PDF Configuration can be set in constant editor or TypoScript setup
* Include Static Typoscript Template in BackendModule "Templates"
* Example Footer and Header HTML files can be found in partialRootPath/Pdf/Header.html (with pagenum and date)

The following settings are available in ``plugin.tx_web2pdf.settings``:

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

   :Default:
         Default:

 - :Field:
         pdfPageFormat

   :Description:
         PDF page format (e.g A4 or A5)

   :Default:
         A4

 - :Field:
         pdfPageOrientation

   :Description:
         Page orientation. Possible values:

         * L = Landscape
         * P = Portrait

   :Default:
         P

 - :Field:
         pdfLeftMargin

   :Description:
         Margin left

   :Default:
         15

 - :Field:
         pdfRightMargin

   :Description:
         Margin right

   :Default:
         15

 - :Field:
         pdfTopMargin

   :Description:
         Margin top

   :Default:
         15

 - :Field:
         pdfBottomMargin

   :Description:
         Margin bottom

   :Default:
         15

 - :Field:
         pdfStyleSheet

   :Description:
         Which style sheet media should be loaded (`all` is always included)

   :Default:
         allAndPrint

 - :Field:
         pdfDestination

   :Description:
         PDF download destination. Possible values:

         * ``attachment`` - download generated PDF file
         * ``inline`` - show generated PDF file in browser

   :Default:
         attachment

 - :Field:
         useCustomHeader

   :Description:
         If set, a custom page header will be added to the PDF document. The template of the header is located in
         ``Resources/Private/Partials/Pdf/Header.html``

   :Default:
         0

 - :Field:
         useCustomFooter

   :Description:
         If set, a custom page footer will be added to the PDF document. The template of the footer is located in
         ``Resources/Private/Partials/Pdf/Footer.html``

   :Default:
         0

 - :Field:
         pdfPregSearch

   :Description:
         Array of search patterns used with :php:`preg_replace` to replace content in the generated PDF

   :Default:
         empty

 - :Field:
         pdfPregReplace

   :Description:
         Array of replacements used for ``pdfPregSearch``

   :Default:
         empty

 - :Field:
         pdfStrSearch

   :Description:
         Array of search strings used with :php:`str_replace` to replace content in the generated PDF

   :Default:
         empty

 - :Field:
         pdfStrSearch

   :Description:
         Array of replacements used for ``pdfStrReplace``

   :Default:
         empty

Example for replacements
------------------------
There are two replacements options: StringReplacement AND/OR PregReplacement
SearchString and ReplacementString need to have the same key

Can be set via TypoScript using following options:
::

	plugin.web2pdf.settings {
		pdfPregSearch {
			1 =
		}

		pdfPregReplace {
			1 =
		}

		pdfStrSearch {
			1 =
		}

		pdfStrReplace {
			1 =
		}

	}
Example: Replace `Hello` with `Good Night`
::

	plugin.web2pdf.settings {

		pdfStrSearch {
			1 = Hello
		}

		pdfStrReplace {
			1 = Good Night
		}
	}
