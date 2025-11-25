# API

Anything configurable through [a config file](config.md) is configurable through the API (and vice versa).

Use of the API is optional, and may be ignored entirely if you wish to rely solely on a config file.

To use the API you must expose the container's port: `8080`.

This document serves as a detailed description of our own [OpenAPI specification](../api/api.yml).
The API is exposed on the container's port `8080`.

Our OpenAPI leaves servers unspecified; the server is determined by how you run the container.
For example, running the container locally, with the API bound to port `8080` on the host, your server would be: http://localhost:8080

> [!NOTE]
> The API can override endpoints defined by the config, but cannot delete them.

## Add Operation

This defines a default response for requests that route to a specific operation.

Operations are added with a POST request to `/operation/{operationId}`.
Where `{operationId}` is the id of the operation you wish to mock.

> [!IMPORTANT]
> A default response is required.
> An operation is considered undefined if it cannot guarantee a defined response.

### Example: Add the showPetById Operation

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

### Example: Add the listPets Operation

```sh
curl \
-X POST \
-H 'Content-type: application/json' \
-d '{
      "default": {
        "response": {
          "code": 200,
          "headers":{"Content-type":"application/json"},
          "body": "[{\"id\":\"rgrtsdg\",\"name\":\"Spike\"},{\"id\":\"rgrtsct\",\"name\":\"Fluffy\"}]"
         }
       }
    }' \
http://localhost:8080/operation/listPets
```

## Delete Operation

This removes the operation if it was defined through the API.
It will also remove any matchers defined for the operation through the API.

It will not remove a [statically configured operation](config.md).

Operations are deleted with a DELETE request to `/operation/{operationId}`.
Where `{operationId}` is the id of the operation you wish to delete.

### Example: Delete the listPets Operation

```
curl -X DELETE http://localhost:8080/operation/listPets
```

## Add Matcher

Specific responses can be given by defining matchers.
The response is given if it *matches* the defined criteria.

> [!IMPORTANT]
> You MUST [add the operation](#add-operation) before you can add a matcher to it.

Matchers are added with a POST request to `/operation/{operationId}/matcher`.
Where `{operationId}` is the id of an operation you have [already defined](#add-operation).

Matchers allow for alternative responses under different circumstances.

### Example: Match listPets With a Limit Less Than Two

```sh
curl \
-X POST \
-H 'Content-type: application/json' \
-d '{
      "matcher":{
        "type":"less-than", 
        "args":{"field":["query", "limit"], "value":2}
      },
      "response": {
        "code": 200,
        "body":"{\"id\":3,\"name\":\"Spike\"}"
      }
    }' \
http://localhost:8080/operation/listPets/matcher
```

"less-than" is an [alias](matcher.md) for one of the [library's built-in matchers](src/Matcher/Matcher).

It takes a field to look at, and a value it should be less than.
This matcher would check the `query` string for the `limit` and check if it is a `value` less than 2.

## Delete Matcher

This will remove a matcher defined through the API.
It will not remove a [statically configured matcher](config.md).

> [!NOTE]
> When [adding a matcher](#add-matcher), your response will contain an `id` field.
> This `id` of the matcher which is required to delete it.

Operations are deleted with a DELETE request to `/operation/{operationId}/matcher/{matcherId}`.
`{operationId}` is the id of the operation you wish to delete.
`{matcherId}` is the id of the matcher you added to the operation.

#### Example: Delete a Matcher On the listPets Operation

Assuming you added a matcher, which returned the following response:

```json
{
    "id":"gk726ejixlvf9b7cy68u",
    "operationId":"listPets",
    "matcher":{
        "type":"less-than",
        "args":{"field":["query","limit"],"value":2}
    },
    "response":{
        "code":200,
        "headers":[],
        "body":"{\"id\":3,\"name\":\"Spike\"}"
    }
}
```

To delete it, we would take the `operationId` and `id` to form the following request.

```
curl -X DELETE http://localhost:8080/operation/listPets/matcher/gk726ejixlvf9b7cy68u
```

## Reset

This removes all operations and matchers defined through the API.

```
curl -X POST http://localhost:8080/reset
```

It will not remove your OpenAPI specification.
It will not remove [statically configured operations or matchers](config.md).
