<?php
return [
    /*
    |--------------------------------------------------------------------------
    | API Subtype
    |--------------------------------------------------------------------------
    |
    | Your subtype will follow the standards tree you use when used in the
    | "Accept" header to negotiate the content type and version.
    |
    | For example: Accept: application/x.SUBTYPE.v1+json
    |
    */

    'subtype' => env('API_SUBTYPE', 'korowai'),

    /*
    |--------------------------------------------------------------------------
    | Name
    |--------------------------------------------------------------------------
    |
    | When documenting your API using the API Blueprint syntax you can
    | configure a default name to avoid having to manually specify
    | one when using the command.
    |
    */

    'name' => env('API_NAME', 'Korowai API'),

    /*
    |--------------------------------------------------------------------------
    | Default API Prefix
    |--------------------------------------------------------------------------
    |
    | A default prefix to use for your API routes so you don't have to
    | specify it for each group.
    |
    */
  'prefix' => env('API_PREFIX', 'api'),

    /*
    |--------------------------------------------------------------------------
    | Scheme
    |--------------------------------------------------------------------------
    |
    | Scheme (transport protocol) used to communicate with the API. Should be
    | one of 'http', 'https' or null. This is used to generate resource links
    | for JSON API responses (only when 'domain' or 'getdomain' is set).
    |
    | If it's null, and 'domain' or 'getdomain' is provided, then korowai will
    | try to deduce scheme automatically from server global variables
    | ($_SERVER['HTTPS'], $_SERVER['REQUEST_SCHEME']) and if it fails, it'll
    | fall back to 'http'.
    */
  'scheme'  =>  env('API_SCHEME', null),

    /*
    |--------------------------------------------------------------------------
    | Get Scheme
    |--------------------------------------------------------------------------
    |
    | A method used to automatically detect API scheme (transport protocol)
    | when the api.scheme is not defined. Possible values are:
    |
    | - _SERVER[HTTPS]          - use $_SERVER['HTTPS'] to detect scheme,
    | - _SERVER[REQUEST_SCHEME] - use $_SERVER['REQUEST_SCHEME'] as scheme
    |
    | The above supported values may be combined with '|' to provide sequence
    | of detection methods (in order of preference).
    |
    | If getScheme is not defined, then all above methods are tried in order.
    */
  'getScheme' => env('API_GET_SCHEME', null),

    /*
    |--------------------------------------------------------------------------
    | Get Domain
    |--------------------------------------------------------------------------
    |
    | A method used to automatically detect API domain when the api.domain
    | is not defined. Possible values are:
    |
    | - _SERVER[SERVER_NAME] - use $_SERVER['SERVER_NAME'] as API domain,
    | - _SERVER[SERVER_ADDR] - use $_SERVER['SERVER_ADDR'] as API domain,
    | - _SERVER[HTTP_HOST]   - use $_SERVER['HTTP_HOST'] as API domain,
    |
    | The above supported values may be combined with '|' to provide sequence
    | of detection methods (in order of preference).
    |
    | If getDomain is not defined, then API domain detection is disabled.
    */

  'getDomain' => env('API_GET_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Response Formats
    |--------------------------------------------------------------------------
    |
    | Responses can be returned in multiple formats by registering different
    | response formatters. You can also customize an existing response
    | formatter with a number of options to configure its output.
    |
    */

    'formats' => [

        'json' => Korowai\Framework\Http\Api\Response\Format\Json::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | API Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware that will be applied globally to all API requests.
    |
    */

    'middleware' => [
        Korowai\Framework\Http\Api\Middleware\SetErrorReplacements::class,
    ],
];
