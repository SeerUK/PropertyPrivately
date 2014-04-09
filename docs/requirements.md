
# Property Privately API

Property Privately shall be powered by a RESTful API service. The API shall be the only method for the front-end to interacr with data, and as such it has to support all of the possible requests that may be made of it. 

---

### Implementation Requirements

This part is mainly for me to be able to visibly see the things I need to write for this to be possible.

* RESTful
* HAL
* JSON Responses
* Response system:
    - Handle HAL aspects in an OO way. Create a HalJsonResponse class that extends the Symfony JsonResponse class.
* Hijack exception handler:
    - Easy to output HTTP status codes this way
    - Easy to compile a list of errors from exception message
    - Can provide other useful tools like logging on certain exceptions (perhaps a parameter for the constructor?)
* Some means of disabling CSRF and enabling a custom solution for handling forms
* API access should be over HTTPS in production. Make sure it damn well is.

### ResourceAssembler

abstract class AbstractResourceAssembler implements ResourceAssemblerInterface 
{
    private $variables;

    abstract public function assemble();

    public function getVariable($name)
    {
        return $this->variables[$name];
    }

    public function setVariable($name, $value) 
    {
        $this->variables[$name] = $value;

        return $this;
    }
}

class UserResourceAssembler extends AbstractResourceAssembler 
{
    public function assemble()
    {
        $resource = new Resource();

        // Do some assembling!

        return $resource;
    }
}
