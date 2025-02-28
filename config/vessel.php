<?php

return [
    'base_uri' => env('VESSEL_BASE_URI', 'https://registry.hub.docker.com/v2/'),
    'jwt_private_key' => env('VESSEL_PRIVATE_KEY'),
    'jwt_public_key' => env('VESSEL_PUBLIC_KEY'),
];
