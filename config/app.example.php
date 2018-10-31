<?php

return [
    'AUTH_HMAC_SECRET' => 'kY73BD*l^&',

    'MYSQL_PASSWORD' => getenv('MYSQL_PASSWORD'),
    'MYSQL_USER' => getenv('MYSQL_USER'),
    'MYSQL_DATABASE' => getenv('MYSQL_DATABASE'),

    'VACATION_DAYS_COUNT' => 20,
    'HOLIDAYS' => [
        '2019-01-01',
        '2019-01-07'
    ]
];