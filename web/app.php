<?php

header('Content-Type: application/json');

require __DIR__.'/../vendor/autoload.php';

$config = include __DIR__.'/../config/app.php';

try {
    throw new Exception('There is nothing to show.');
} catch (Exception $e) {
    echo json_encode(
        [
            'error' => [
                'message' => $e->getMessage(),
            ],
        ]
    );
}
