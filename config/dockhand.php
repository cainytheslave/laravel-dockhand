<?php

return [
    'base_uri' => env('DOCKHAND_BASE_URI', 'https://registry.hub.docker.com/v2/'),
    'jwt_private_key' => env('DOCKHAND_PRIVATE_KEY'),
    'jwt_public_key' => env('DOCKHAND_PUBLIC_KEY'),
    'authority_name' => env('DOCKHAND_AUTHORITY_NAME', 'auth'),
    'registry_name' => env('DOCKHAND_REGISTRY_NAME', 'registry'),
];
