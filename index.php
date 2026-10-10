<?php
/*
 * Implement *citeproc-php* in Kirby,
 * https://github.com/pkp/citeproc-php
 * a CSL processor that renders bibliographic metadata
 * into HTML-formatted citations or bibliographies 
 * using CSL stylesheets
 */

include "vendor/autoload.php";
use Seboettg\CiteProc\StyleSheet;
use Seboettg\CiteProc\CiteProc;
use Kirby\Filesystem\F;
use Kirby\Cms\Page;
use Kirby\Data\Json;

Kirby::plugin('corentinbrule/citeproc', [
    'options' => [
        'data_file_name' => '',
        'csl_name' => '',
        'csl_location' => 'en-EN',
        'additional_markup' => [],
        'additional_markup_default' => true
    ],
    'tags' => [
        'cite' => [
            'attr' => [
                'style',
                'additional_markup'
            ],
            'html' => function ($tag): string {
                $page = $tag->parent();
                if ($page instanceof Page) {
                    if ($page->bibliography()->biblioData()) {
                        $data = $page->bibliography()->biblioData();
                    }
                    if (option('corentinbrule.citeproc.additional_markup_default') == true) {
                        $additionalMarkup = option('corentinbrule.citeproc.additional_markup');
                    } else {
                        if ($tag->additional_markup == true || $tag->additional_markup == "true") {
                            $additionalMarkup = option('corentinbrule.citeproc.additional_markup');
                        } else {
                            $additionalMarkup = [];
                        }
                    }
                    $style = StyleSheet::loadStyleSheet(option('corentinbrule.citeproc.csl_name'));
                    $location = option('corentinbrule.citeproc.csl_location');
                    $citeProc = new CiteProc($style, $location, $additionalMarkup);
                    return $citeProc->render($data, "citation", json_decode('[{"id":"' . $tag->value . '"}]'));
                }
            }
        ],
        'biblio' => [
            'attr' => [
                'style'
            ],
            'html' => function ($tag): string {
                $page = $tag->parent();
                if ($page instanceof Page) {
                    if ($page->bibliography()->biblioData()) {
                        $data = $page->bibliography()->biblioData();
                    }
                    $style = StyleSheet::loadStyleSheet(option('corentinbrule.citeproc.csl_name'));
                    $additionalMarkup = option('corentinbrule.citeproc.additional_markup');
                    $location = option('corentinbrule.citeproc.csl_location');
                    $citeProc = new CiteProc($style, $location, $additionalMarkup);
                    return $citeProc->render($data, "bibliography");
                }
            }
        ]
    ],
    'blueprints' => [
        'fields/bibliography' => __DIR__ . '/fields/bibliography.yml',
    ],
    'fieldMethods' => [
        'biblioData' => function ($field) {
            if ($field->key() == 'bibliography') {
                if($field->toFile()){
                    $biblio = $field->toFile()->root();
                    $data = F::read($biblio);
                    $json = json_decode($data);
                    return $json;
                }else{
                    return false;
                }
                
            }
        },
    ],
    'snippets' => [
        'citeproc_cite' => __DIR__ . '/snippets/cite.php',
        'citeproc_table' => __DIR__ . '/snippets/table.php',
        'citeproc_bibliography' => __DIR__ . '/snippets/bibliography.php',
    ],
]);