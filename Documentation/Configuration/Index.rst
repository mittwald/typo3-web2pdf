.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration Reference
=======================

Target group: **Developers**


.. _configuration-typoscript:

Template
--------
Just override ``plugin.tx_web2pdf.view`` to use you own template. Default Template is found in ``EXT:web2pdf/Resources/Private/Templates/Pdf/GeneratePdfLink.html``

TypoScript Reference
--------------------

* PDF Configuration can be set in constant editor or TypoScript
* Include Static Typoscript Template in BackendModule "Templates"
* Example Footer and Header HTML files can be found in partialRootPath/Pdf/Header.html (with pagenum and date)
::

	plugin.web2pdf.settings {
		# e.g.: A4, A5, etc
		pdfPageFormat = {$plugin.tx_web2pdf.settings.pdfPageFormat}
		# L = Landscape P = Portrait
		pdfPageOrientation = {$plugin.tx_web2pdf.settings.pdfPageOrientation}
		pdfLeftMargin = {$plugin.tx_web2pdf.settings.pdfLeftMargin}
		pdfRightMargin = {$plugin.tx_web2pdf.settings.pdfRightMargin}
		pdfTopMargin = {$plugin.tx_web2pdf.settings.pdfTopMargin}
		# which stylesheet to use: print or screen stylesheet
		pdfStyleSheet = {$plugin.tx_web2pdf.settings.pdfStyleSheet}
		# use header partial in plugin.web2pdf.view.partialRootPath/Pdf/Header.html
		useCustomHeader = 0
		# use footer partial in plugin.web2pdf.view.partialRootPath/Pdf/Footer.html
		useCustomHeader = 0
	}

Replacements
------------
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


Problems with `indexed_search`
------------------------------
By default web2pdf links/views are indexed by `indexed_search`. To avoid this behaviour you have to configure a new page type for web2pdf views.
::

	web2pdfPage < yourDefaultPage
	web2pdfPage.typeNum = 9765123
	[globalVar = GP:type = 9765123]
		config.index_enable = 0
	[global]

Additionally you have to edit the link template in `EXT:web2pdf/Resources/Private/Templates/Pdf/GeneratePdfLink.html`
::

	<f:link.action action="" arguments="{argument:settings.pdfQueryParameter}" pageType="9765123" addQueryString="1">
		<f:translate key="web2pdf.printLabel" />
	</f:link.action>

