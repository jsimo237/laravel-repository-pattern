<?php

return [
    "repositories_namespace" => env("REPO_PATTERN_NAMSPACE","App\\Repositories"),
    "models_namespace" => env("REPO_PATTERN_NAMSPACE","App\\Models"),
    "controllers_namespace" => env("REPO_PATTERN_NAMSPACE","App\\Http\\Controllers"),
    "resources_namespace" => env("REPO_PATTERN_NAMSPACE","App\\Http\\Resources"),

    "required_model" => env("REPO_PATTERN_REQUIRED_MODEL",true),

    "required_resource" => env("REPO_PATTERN_REQUIRED_RESOURCE",true),

    "required_validator" => env("REPO_PATTERN_VALIDATOR",true),


    /*
     * The name of the query parameter used for pagination
     */
    'pagination_parameter' => 'page',
];