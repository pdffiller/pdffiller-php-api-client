<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class Folder
 * @package PDFfiller\OAuth2\Client\Provider
 * @property string $name
 * @property string $documents_count
 * @property string $folders_count
 * @property string $parent_id
 * @property string $created
 * @property string $folder_id
 */
class Folder extends Model
{
    protected $primaryKey = 'folder_id';
}
