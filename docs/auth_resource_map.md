
# Authentication Resource Map

## Required

User
/user
 - Roles (Shown)
 - Applications (Linked)
     + @ /user/applications
 - Tokens (Linked)
     + @ /user/tokens

Applications
/user/applications
 - User (Owner, Linked)
     + @ /users/<user id>
 - Tokens (Of owner, Linked)
     + @ /user/applications/<application id>/tokens

Application
/user/applications/<application id>
 - User (Owner, Linked)
    @ /users/<user id>
 - Tokens (Of owner, Linked)
     + @ /user/applications/<application id>/tokens

Tokens
/user/tokens
 - User (Linked)
    @ /users/<user id>
 - Application (Shown)

Token
/user/tokens/<token id>
 - User (Linked)
    @ /users/<user id>
 - Application (Shown)


## Optional:

Roles
/roles
 - Users (Linked)

Role
/role/<role id>
 - Users (Shown)
