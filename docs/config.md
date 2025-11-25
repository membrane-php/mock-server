# Static Configuration

Anything configurable through [the API](api.md) is configurable through a config file (and vice versa).

Use of a config file is optional, and may be omitted entirely, if you wish to rely solely on the API.

To use a config file you must mount it to the container at `/config/config.php`.

Your config file is expected to return an array. 
This is the minimum viable config:

```php
<?php return [];
```

> [!NOTE]
> The endpoints defined by the config can be overriden by the API at runtime.
> Anything defined by the config cannot be deleted by the API.
> Calling reset on the API will not affect endpoints defined by the config.

## Define an Operation

Your config defines behaviour for operations in your API.
The operationIds are keys, the config for those operations are the values.

> [!IMPORTANT]
> A default response is required.
> An operation is considered undefined if it cannot guarantee a defined response.

### Example: Add the showPetById Operation

```php
<?php

return [
    'showPetById' => [
        'default' => [
            'response' => [
                'code' => 404,
            ],
        ],
    ],
];
```

For responses that only define a status code, your config can be compacted. The examples above and below are functionally identical.

```php
<?php

return [
    'showPetById' => [
        'default' => [
            'response' => 404,
        ],
    ],
];
```

### Example: Add the listPets Operation

For responses that need a response body, it can be supplied in two ways:
- A string, to be taken exactly as it is.
- An array, to be encoded as JSON.

```php
<?php

return [
    'listPets' => [
        'default' => [
            'response' => [
                'code' => 200,
                'headers' => [
                    'Content-type' => 'application/json',
                 ],
                 'body' => [
                     [
                         'id' => 'rgrtsdg',
                         'name' => 'Spike',
                     ],
                     [
                         'id' => 'rgrtsct',
                         'name' => 'Fluffy',
                     ],
                 ],
            ],
        ],
    ],
];
```

## Define a Matcher

Specific responses can be given by defining matchers.
The response is given if it *matches* the defined criteria.

```php
<?php

return [
    'listPets' => [
        'default' => [
            'response' => [
                'code' => 200,
                'headers' => [
                    'Content-type' => 'application/json',
                 ],
                 'body' => [
                     [
                         'id' => 'rgrtsdg',
                         'name' => 'Spike',
                     ],
                     [
                         'id' => 'rgrtsct',
                         'name' => 'Fluffy',
                     ],
                 ],
            ],
        ],
        'matchers' => [
            [
                'matcher' => [
                    'type' => 'less-than',
                    'args' => [
                        'field' => ['query', 'limit'],
                        'value' => 2,
                    ],
                ],
                'response' => [
                    'code' => 200,
                    'body' => '{"id":"rgrtsdg", "name":"Spike"}',
                ],
            ],
        ],
    ],
];
```

> [!IMPORTANT]
> Even if you define a matcher, your operation MUST have a default to be considered *defined*.
