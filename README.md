# Membrane Mock Server

A configurable mock server for your [OpenAPI Specification](https://spec.openapis.org/oas/v3.1.0.html),

> [!IMPORTANT]
> Every [operation](https://spec.openapis.org/oas/v3.1.0.html#operation-object) requires an [operationId](https://spec.openapis.org/oas/v3.1.0.html#fixed-fields-7).  
> The mock server uses them to identify your endpoints.

## Quickstart

To demonstrate we're going to use the classic: [petstore.yml](https://learn.openapis.org/examples/v3.0/petstore.html#yaml).

### Run the Mock Server
```sh
docker run ghcr.io/membrane-php/mock-server:base-182-gff6566 \
-v ~/myapp/api/petstore.yml:/api/api.yml \
-p 8081:8081 \
-p 8080:8080
```

Your API's mock server is exposed on port `8081`.

Membrane Mock Server's [API](docs/api.md) is exposed on port `8080`.




### Configure through API

Example: showPetById

[more detail](docs/api.md).

### Mount Static Config File

Example:

[more detail](docs/config.md)


## Usage

The mockserver exposes two ports.

`8080` is [the mock server's API](#the-api); used for defining [operations](#operation) and [matchers](#matcher).

`8081` exposes [the mock](#the-mock) of your OpenAPI.

## The API

Naturally, the API is also defined by an OpenAPI spec.
It can be read [here](api/api.yml)

### Add Operation

Send a POST request to the api's [add-operation](api/api.yml) endpoint.

The body must contain a valid config for the Operation, as defined by the api.

#### Adding a Default Response

If the server is hosted on your localhost, and you're mocking the [Swagger Petstore](https://learn.openapis.org/examples/v3.0/petstore.html);
to mock the `showPetById` operation, you could send the following request.

```
POST http://localhost:8080/operation/showPetById

{
    "default": {
        "response": {
            "code": 200,
            "body": {
                "id": 5,
                "name": "Spike"
            }
        }
    }
}
```

Now, by default, any valid request to `showPetById` will receive a 200 response with the pet who has the id: 5.

However, a default alone is not always ideal. If you request the pet with id: 7.

```
GET http://localhost:8081/pets/7
```

You still receive a 200 response with the pet who has id: 5.

Ideally, only `GET http://localhost:8081/pets/5` should match id: 5.

To redefine our Operation, the existing definition needs to be [deleted](#delete-operation) first (or the entire server can be [reset](#reset)).

Once that is done, we can add the operation with a new definition:

```
POST http://localhost:8080/operation/showPetById

{

    "default": {
        "response": {
            "code": 404
        }
    }
}
```

This would, by default, return a 404.
Then you could [add a matcher](#add-matcher) for specific ID's, returning pets with the corresponding ID. 


### Delete Operation

To delete an operation make the following request:

```
DELETE http://localhost:8080/operation/<operationId>
```

Where `<operationId>` is the `operationId` of the operation you want to delete.

### Add Matcher

```
    "matchers": [
        {
            "matcher": {
                "type": "equals",
                "args": {
                    "field": ["path", "petId"],
                    "value": 3
                }
            },
            "response": 200,
        }
    ],
```

It returns a 200 response only if your "petId" *equals* 5.

*equals* is an [alias](src/Matcher/Module.php) for one of the [library's built-in matchers](src/Matcher/Matcher).

### Delete Matcher

### Reset

## The Mock

If you call an endpoint, before [adding the operation](#add-operation), then you will receive a 522 response. 522 is a non-standard HTTP code, chosen to reduce the risk of conflicting with your OpenAPI spec. It is intended to imply similar meaning to a [422](https://www.rfc-editor.org/rfc/rfc4918.html#section-11.2): Your request was valid against your OpenAPI spec, but you had not [defined a response for the operation](#add-operation) it routed to.



When calling the MockServer:
1. Your request will be validated against your OpenAPI spec.
   a. A valid request will route to an `operationId`.
2. Your request will be checked against the defined config for that operation.
   a. If no config exists
