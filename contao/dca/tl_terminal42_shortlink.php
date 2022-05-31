<?php

$GLOBALS['TL_DCA']['tl_terminal42_shortlink'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'ctable'                      => ['tl_terminal42_shortlink_log'],
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 2,
            'fields'                  => array('target DESC'),
            'flag'                    => 1,
            'panelLayout'             => 'filter;sort,search,limit',
        ),
        'label' => array
        (
            'fields'                  => array('alias', 'target', 'log', 'dateAdded'),
            'showColumns'             => true,
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ),
        ),
        'operations' => array
        (
            'edit' => array
            (
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ),
            'copy' => array
            (
                'href'                => 'act=copy',
                'icon'                => 'copy.svg'
            ),
            'delete' => array
            (
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? '') . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
            (
                'icon'                => 'visible.svg',
                'haste_ajax_operation'  => [
                    'field'     => 'published',
                    'options'    => [
                        [
                            'value'     => '',
                            'icon'      => 'invisible.gif'
                        ],
                        [
                            'value'     => '1',
                            'icon'      => 'visible.gif'
                        ],
                    ],
                ],
            ),
            'show' => array
            (
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            ),
        ),
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{url_legend},target,alias,name;{publishing_legend},published',
    ),

    // Fields
    'fields' => array
    (
        'target' => array
        (
            'exclude'                 => true,
            'sorting'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'dcaPicker'=>true, 'addWizardClass'=>false, 'tl_class'=>'clr'),
        ),
        'alias' => array
        (
            'exclude'                 => true,
            'sorting'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'url', 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
        ),
        'name' => array
        (
            'exclude'                 => true,
            'sorting'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
        ),
        'published' => array
        (
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
        ),
        'dateAdded' => array
        (
            'default'                 => time(),
            'sorting'                 => true,
            'flag'                    => 6,
            'eval'                    => array('rgxp'=>'datim', 'doNotCopy'=>true),
        ),
        'log' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_terminal42_shortlink']['log'],
        ),
    ),
);
