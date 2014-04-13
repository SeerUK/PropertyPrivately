
# TODO List

* Move user token generator action into SecurityBundle TokenController class
* Make the token generator route RESTful:

    POST /user/tokens, roles: `IS_AUTHENTICATED_ANONYMOUSLY`
    X-API-App-Secret: <app secret>
    {
        "username": <username>,
        "password": <password>
    }
    HTTP/1.1: 201
    Location: /user/tokens/<generated token id>

    GET /user/tokens, roles: `IS_AUTHENTICATED_FULLY`
    X-API-Key: <api key>
    {
        "total": <number of tokens for current user>,
        "_links": {
            "self": "/user/tokens"
        },
        "_embedded": {
            "tokens": [
                <tokens>
            ]
        }
    }
    HTTP/1.1: 200

    GET /user/tokens/<token id>, roles: `IS_AUTHENTICATED_FULLY`
    X-API-Key: <api key>
    {
        <full token data>,
        "_links": {
            "self": "/user/tokens/<token id>"
        }
    }
    HTTP/1.1: 200

    PUT /user/tokens/<token id>, roles: `IS_AUTHENTICATED_FULLY`
    X-API-Key: <api key>
    {
        "description": <updated token description>
    }
    HTTP/1.1: 200
    Return the updated token entity, as if you were looking at the get for the token (maybe do an internal redirect/forward to avoid duplication?)

    DELETE /user/tokens/<token id>, roles: `IS_AUTHENTICATED_FULLY`
    X-API-Key: <api key>
    HTTP/1.1: 204

* Implement VariableModelInterface in RestBundle

