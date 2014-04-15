
# Security Resources

All of the following require full authentication.

## Tokens

User tokens:
/user/tokens 

    GET    /user/tokens - Get all tokens
    POST   /user/tokens - Create a new token - [App Key] - {Username, Password}
    DELETE /user/tokens - Delete all tokens

User token <id>:
/user/tokens/<id>

    GET    /user/tokens/1 - Get token 1
    PATCH  /user/tokens/1 - Partially update token 1 - {Description}
    DELETE /user/tokens/1 - Delete token 1

User tokens of application <id>:
/user/tokens/application/<id>

    GET    /user/tokens/application/1 - Get all tokens from application 1
    DELETE /user/tokens/application/1 - Delete all tokens from application 1

## Applications

User applications:
/user/applications

    GET    /user/applications - Get all applications
    POST   /user/applications - Create a new application - {Name, Description}
    DELETE /user/applications - Delete all applications

User application <id>:
/user/applications/<id>

    GET    /user/applications/1 - Get application 1
    PATCH  /user/applications/1 - Partially update application 1 {Name, Description}
    DELETE /user/applications/1 - Delete application 1
