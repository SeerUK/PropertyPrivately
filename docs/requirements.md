
# Property Privately API

Property Privately shall be powered by a RESTful API service. The API shall be the only method for the front-end to interacr with data, and as such it has to support all of the possible requests that may be made of it. 

---

### Implementation Requirements

This part is mainly for me to be able to visibly see the things I need to write for this to be possible.

* RESTful
* JSON Responses
* RestController:
    - Should provide easy access to new RESTful methods described below
    - When a view is returned, it should be encoded to the correct format automatically, this decision should be be taken away from the action. 
        + Possibly used in routing?
* Hijack exception handler:
    - Easy to output HTTP status codes this way
    - Easy to compile a list of errors from exception message
    - Can provide other useful tools like logging on certain exceptions (perhaps a parameter for the constructor?)
* View system:
    - Potentially handle HAL aspects in an OO way. Maybe create a HalJsonResponse class that extends the Symfony JsonResponse class?
* Some means of disable CSRF and enabling a custom solution.
* Utility classes:
    - HTTP status codes in plain text class
    - Formatters for typical response types (i.e. exceptions say?)