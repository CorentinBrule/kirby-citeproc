# Kirby Citeproc

Implementation of [citeproc-php](https://github.com/pkp/citeproc-php/) in Kirby as tags and snippets.

`citationKey` + `csl-json.json` + `style.csl` +  = full HTML formated citation. 

## install

This plugin is based of a [fork of citeproc-php](https://github.com/corentinbrule/citeproc-php/), a CSL processor for PHP. This core component of the plugin is currently in a transitional phase following the departure of its long-standing maintainer; it must therefore be installed in a specific way to resolve dependency issues and be used in the Kirby context.

Furthermore, the fork gives you the option of using the entire CSL style database by downloading it in full as a dependency, or to add `.csl` files manually.

### with Composer:

```bash
php composer.phar install --no-dev
```

## features

- tags to convert a citation key to formated html.
- snippets to display bibliography on formated html or on searchable and sortable table.
- CSL-JSON file blueprint

## TODO:

- CSL style file blueprint
- fix idib. issues in citeproc-php
- validation file .CSL et CSL-JSON with Json-schema. With this kind of dependances :

```
{
    "name": "corentinbrule/citeproc",
    "repositories": [
        {
            "type": "composer",
            "url": "https://repo.packagist.org"
        },
        {
        "type": "vcs",
        "url": "https://github.com/citation-style-language/schema.git"
        }
    ],
    "require": {
        "seboettg/citeproc-php": "2.5.2",
        "seboettg/collection": "^3.1",
        "citation-style-language/locales": "0.0.*",
        "justinrainbow/json-schema": "^6.13",
        "citation-styles-language/schema": "master"
    }
}

```