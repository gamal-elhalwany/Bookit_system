<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:3001'], // رابط الفرونت
    'allowed_headers' => ['*'],
    'supports_credentials' => false,
];
