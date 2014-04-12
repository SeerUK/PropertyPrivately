
# Authentication

## Method

One time request to API over HTTPS, lets' say:

    POST /api/v1/user/login
    Data:
    {
        "username": "Example",
        "password": "ExamplePass1"
    }

Which (if successful) would in turn return a response similar to the following (pretend that this token is something valid):

    {
        "token": "$sdflKJOjf$wieni24rh98a\j98jwro2nriwjefosij09JR332IJnjnoiLSDF",
        "_links": {
            "self": "/api/v1/user/login"
        }
    }

Upon this successful request, the service would store the token it generates in a token table with a reference to the user it's authenticating. The client would then take the token, store it in a cookie on the client side. This token would then be sent as a header to the API with every request to authenticate the user.

## Problems

* If there are different clients implemented would we allow multiple tokens for a single user to be used? Or would any old token(s) be invalidated when a new one was created?

* How do we deal with timing out the tokens? Have a script that automatically removes old tokens from the DB? (Seems a little lame?) Even if tokens were handled on a per client basis this would be an issue, because we wouldn't want to give a user a token and NEVER revoke it, that would mean if someone got hold of the token they would be able to stay logged in for a looooong time possible.
    - When you change a password, get rid of all tokens for a user.
    - Limit tokens

* How would we handle 2 different clients using the same token? This SHOULD ideally invalidate the token.

* Should there be some kind of private token in the client and server? If this is the case, how would third party apps deal with this? (Possible to restrict the API to only be used with in-house client, but then how do places like Twitter deal with having a 'public' API?)
    - In this case, the private key (or secret key, whatever) would be used to identify an application, and not necessarily be there for the users safety. This still poses the question, how would requests over something like CURL be handled? Would there have to be some secret key involved there? I don't think there should be?
    - There should also be a section in the user control panel to delete all the tokens that are 'validated' for the current user. Effectively logging every session out and forcing new tokens to be generated on each application.

## Solutions

* Applications given IDs
    - Applications have scopes, i.e. permissions
        + Trusted, first-party apps can create new applications and scopes
        + Trusted, first party apps can link user tokens with application tokens, thereby allowing a user token to be used through an app. (Some sort of flag on the token itself: i.e. validated tinyint(1)?)
    - Tokens 
        + ALWAYS have an application registered to them
        + Have to be validated by a trusted application or the API to be used
        + Linked to a user
        + Must have a created timestamp
        + Have a description that is created by the client.
            * PP Website would take the browser and version

## Implementation

* Authentication token generated when a user submits JSON encoded login credentials to /v1/user/login (may be subject to change...) 

Should this be:

    POST /v1/user/tokens, data:
    {
        "username": "<USERNAME>",
        "password": "<PASSWORD>"
    }

So that we're conforming more to rest standards? Then let's say, in the API we have something like:

    GET /v1/user/tokens, response:
    {
        "total": 1,
        "_links": {
            "self": "/v1/user/tokens"
        },
        "_embedded": {
            "tokens": [{
                "id": 1,
                "token": "<TOKEN HERE">,
                "_links": {
                    "self": "/v1/user/tokens/1"
                },
                "_embedded": {
                    "app": {
                        "id": 1,
                        "name": "CURL",
                        "_links": {
                            "self": "/v1/applications/1"
                        }
                    }
                }
            }]
        }
    }

Other methods:

    DELETE /v1/user/tokens/1 - Deletes the given token
    PATCH  /v1/user/tokens/1 - Updates a segment of the given token (probably only the name).

There shall be no implementation of PUT, as we should never be able to replace a token. 

* Authentication token sent in 'X-API-Key' custom header.