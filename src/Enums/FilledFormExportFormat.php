<?php

namespace PDFfiller\OAuth2\Client\Provider\Enums;
use PDFfiller\OAuth2\Client\Provider\Core\Enum;

/**
 * Class SignatureRequestMethod
 * @package PDFfiller\OAuth2\Client\Provider\Core
 */
class FilledFormExportFormat extends Enum
{
    const JSON = 'json';
    const XLS = 'xls';
    const XLSX = 'xlsx';
    const CSV = 'csv';
    const HTML = 'html';
    
    const __default = self::JSON;
}
