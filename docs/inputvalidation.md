
# Input Validation

## Controller:

Example controller implementation:
    $filter = $this->createInputFilter(new RegistrationType(), [
        'user'   => new User(),
        'person' => new Person()
    ])->dispatch(json_decode($request->getContent(), true));

## Process

* Create input filter based on defined type, and give it model(s), either empty or with data.
    - Model(s) type should be validated against the type
        + On type, specify the type of model(s) the type supports

* Input filter will attach data given to it to the model
* Input filter will validate it instantly upon receiving data. The model(s) shall be validated with the standard validator, and given the validation groups. Any exceptions will be caught.
    - This stage must also map the properties of the model that has an error to the field that was the source of the error.
* Input filter will then 'lock' and store the result of the validation somewhere.
* The method `isValid()` will determine the validity of the model(s) and thus, the input filter as a whole. If there are any errors they will be available later. `isValid()` will return either true or false.
    - These errors will then be retrievable by calling `getErrors()` on the filter.

The rest is handled in the controller.

### Type Definition

Requirements:

* Type must allow multiple models, NOT multiple types to be embedded.
* Data is mapped on a single layer, straight to an entity type.
* No 2 fields can share a name. 
    - i.e. no name on both User and Person if both models were present.
        + In this case, different field names would be specified, and mapped accordingly to a different property. 

