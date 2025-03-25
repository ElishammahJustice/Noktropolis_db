<?php

return [
    'paths' => ['api/*'], // Apply CORS to API routes
    'allowed_methods' => ['*'], // Allow all HTTP methods (GET, POST, PUT, DELETE, OPTIONS)
    'allowed_origins' => ['*'], 
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Allow all headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Needed if using authentication (e.g., Sanctum)
];
