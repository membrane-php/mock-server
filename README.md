# Membrane Mock Server

A configurable mock server for your [OpenAPI Specification](https://spec.openapis.org/oas/v3.1.0.html),

> [!IMPORTANT]
> Every [operation](https://spec.openapis.org/oas/v3.1.0.html#operation-object) requires an [operationId](https://spec.openapis.org/oas/v3.1.0.html#fixed-fields-7).  
> The mock server uses them to identify your endpoints.

## Quickstart

> ![NOTE]
> All examples are based on [petstore.yml](https://learn.openapis.org/examples/v3.0/petstore.html#yaml).

### Run the Mock Server

The following command will run the container, if you:
- replace `<path-to-your-api>` with the file path to your api.

```sh
docker run \
-v <path-to-your-api>:/api/api.yml \
-p 8081:8081 \
-p 8080:8080 \
 ghcr.io/membrane-php/mock-server:0.1.0
```

Your API's mock server is exposed on port `8081`.

Membrane Mock Server's [API](docs/api.md) is exposed on port `8080`.

By default:
- Requests that are not defined by your OpenAPI receive a `404` or `405` @TODO
- Requests that are invalid, according to your OpenAPI, receive a `400`; bad request.
- Requests that are valid, but you have not explicitly defined, receive a custom `522`; response not defined. A non-standard code has been picked to reduce risk of conflicts with your OpenAPI.

### Configure through API

#### Define the Default Response For an Operation

We minimal definition for an operation can be defined like so:

```sh
curl \
-X POST \
-H 'Content-type: application/json' \
-d '{
      "default": {
        "response": {
          "code": 404
         }
       }
    }' \
http://localhost:8080/operation/showPetById
```

To find out what else can be defined, [read more on the api](docs/api.md), or go straight to [the source](api/api.yml).

The above will define the `showPetById` operation to return a 404 (not found) whenever a request such as `curl http://localhost:8081/pets/3 ` are made.

#### Define a Response For Specific Requests To an Operation

We can now define a response for a request with id 3.

```sh
curl \
-X POST \
-H 'Content-type: application/json' \
-d '{
      "matcher":{
        "type":"equals", 
        "args":{"field":["path", "petId"], "value":3}
      },
      "response": {
        "code": 200,
        "body":"{\"id\":3,\"name\":\"Spike\"}"
      }
    }' \
http://localhost:8080/operation/showPetById/matcher
```

Afterwards `curl http://localhost:8081/pets/3` will return a successful response.

Any other request will still return the default response.

More detail on configuring through the API can be [found here](docs/api.md).

### Configure through PHP

Alternatively, you can forgo the API entirely in favour of static configuration files written in PHP.

```php
<?php

return [
    'showPetById' => [
       'default' => ['response' => ['code' => 404]],
       'matchers' => [
           [
               'matcher' => [
                   'type' => 'equals',
                   'args' => [
                       'field' => ['path', 'petId'], 
                       'value' => '3',
                   ]
               ],
               'response' => [
                   'code' => 200,
                   'body' => '{"id":"3", "name":"Spike"}',
               ],
           ],
       ],
    ],
];
```

Your config should be mounted to /config/config.php

```sh
docker run \
-v <path-to-your-api>:/api/api.yml \
-v <path-to-your-config>:/config/config.php \
-p 8081:8081 \
ghcr.io/membrane-php/mock-server:0.1.0
```

This will achieve the same result as earlier, but without having to touch the API at all (Note how we can even omit exposure of the API's port when running the container).

For more detail on config options, [check here](docs/config.md).
