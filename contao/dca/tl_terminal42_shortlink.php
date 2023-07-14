<?php

$GLOBALS['TL_DCA']['tl_terminal42_shortlink'] = [

    // Config
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'ctable' => ['tl_terminal42_shortlink_log'],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 2,
            'fields' => ['target DESC'],
            'flag' => 1,
            'panelLayout' => 'filter;sort,search,limit',
        ],
        'label' => [
            'fields' => ['alias', 'target', 'log', 'dateAdded'],
            'showColumns' => true,
        ],
        'global_operations' => [
            'all' => [
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'edit' => [
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'copy' => [
                'href' => 'act=copy',
                'icon' => 'copy.svg',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? '').'\'))return false;Backend.getScrollOffset()"',
            ],
            'toggle' => [
                'icon' => 'visible.svg',
                'haste_ajax_operation' => [
                    'field' => 'published',
                    'options' => [
                        [
                            'value' => '',
                            'icon' => 'invisible.gif',
                        ],
                        [
                            'value' => '1',
                            'icon' => 'visible.gif',
                        ],
                    ],
                ],
            ],
            'show' => [
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ],
    ],

    // Palettes
    'palettes' => [
        'default' => '{url_legend},target,alias,name;{publishing_legend},published',
    ],

    // Fields
    'fields' => [
        'target' => [
            'exclude' => true,
            'sorting' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'rgxp' => 'url', 'decodeEntities' => true, 'dcaPicker' => true, 'addWizardClass' => false, 'tl_class' => 'clr'],
        ],
        'alias' => [
            'exclude' => true,
            'sorting' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'url', 'unique' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
        ],
        'name' => [
            'exclude' => true,
            'sorting' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
        ],
        'published' => [
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
        ],
        'dateAdded' => [
            'default' => time(),
            'sorting' => true,
            'flag' => 6,
            'eval' => ['rgxp' => 'datim', 'doNotCopy' => true],
        ],
        'log' => [
            'label' => &$GLOBALS['TL_LANG']['tl_terminal42_shortlink']['log'],
        ],
    ],
];
