<?php
use PDFfiller\OAuth2\Client\Provider\User;

$provider = require_once __DIR__ . '/../bootstrap/initWithFabric.php';

$e = User::me($provider);
dd($e);
