.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt
.. _known-problems:

Known Problems
==============

Plugin must be on page being printed
------------------------------------

If you use the PSR-15 MiddleWare exclusive (e.g. control GET parameters yourself without using the included plugin),
the TYPO3 exception `Setup array has not been initialized. This happens in cached Frontend scope where full TypoScript`
may be thrown. It is therefore required to place the plugin on the page being printed as PDF.

