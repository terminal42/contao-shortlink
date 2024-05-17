<?php

use Terminal42\ShortlinkBundle\Controller\QrCodeController;

$GLOBALS['BE_MOD']['content']['shortlink'] = [
    'tables' => ['tl_terminal42_shortlink', 'tl_terminal42_shortlink_log'],
    'qrCode' => [QrCodeController::class, '__invoke'],
];
