
# Property Privately API

Property Privately shall be powered by a RESTful API service. The API shall be the only method for the front-end to interacr with data, and as such it has to support all of the possible requests that may be made of it. 

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
    - Possibly make use of view models and presenters again
        + View models should be identical to the ones used in DWI
        + Presenters could do with a different name, and being a bit more reusable (i.e. if there is more than one place preparing a view model for displaying a house, that's silly, make it DRY)
    - Could potentially contain information related to the request for debugging. 
        + Maybe have a debugging mode for this kind of thing, rather than this information being prepared all the time. Though, it shouldn't cause much overhead), i.e. route, parameters, format, dump of template variables, status code (should also be part of the response on error)
* Some means of disable CSRF and enabling a custom solution.
* Utility classes:
    - HTTP status codes in plain text class
    - Formatters for typical response types (i.e. exceptions say?)