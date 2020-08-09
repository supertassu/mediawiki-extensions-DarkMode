DarkMode
========

A MediaWiki extension to add a toggleable dark mode for the MediaWiki user interface.

This repository contains my modifications that add a very simple way to persist the option.
The [upstream](https://github.com/wikimedia/mediawiki-extensions-DarkMode) does not support
that yet ([T241925](https://phabricator.wikimedia.org/T241925)) because they want to do it
properly with support for non-logged-in users and something like that. I just added a hidden
user preference.

