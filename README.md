glossary-m34
==========

WordPress Glossary Pugin.

## Changelog

### 0.2

+ Installation and configuration instructions added to README file.

## Installation

+ Download the plugin from the github repo: https://github.com/skotperez/glossary-m34
+ Upload the ZIP file to your WordPress installation using the Add plugin option: http://example.org/wp-admin/plugin-install.php?tab=upload
+ Activate the plugin in the plugins list: http://example.org/wp-admin/plugins.php

## Configuration

This plugins creates a type of content (a custom post type) for the glossary and two taxonomies to classify the glossary terms. By default:

+ The post type will be named "glossary"
+ Taxonomies will be named "group" and "letter"

This can be changed in lines 12, 13 and 14 of [glossary-m34.php](https://github.com/skotperez/glossary-m34/blob/master/glossary-m34.php#L12-L14) file.

## Integration with your theme

Glossary can be outputed anywhere in your WordPress site using shortcodes.

+ [m34glossary] outputs list of terms ordered by letters
+ [m34glossary_letters] outputs letters list
+ [m34glossary_groups] outputs groups list


