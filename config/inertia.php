<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Server Side Rendering
    |--------------------------------------------------------------------------
    |
    | These options configure if the Inertia SSR server should be used when
    | pre-rendering initial visits made to your application.
    |
    | You can setup a dedicated SSR server using: `php artisan inertia:start-ssr`
    |
    */

    'ssr' => [
        'enabled' => false,
        'url' => 'http://127.0.0.1:13714',
        'bundle' => base_path('bootstrap/ssr/ssr.mjs'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing
    |--------------------------------------------------------------------------
    |
    | The values described here are used to locate Inertia components on the
    | filesystem. For instance, when using `assertInertia`, the assertion
    | attempts to locate the component name within the paths listed here.
    |
    */

    'testing' => [
        'ensure_pages_exist' => true,
        'page_paths' => [
            resource_path('js/Pages'),
        ],
        'page_extension' => 'vue',
    ],

];
