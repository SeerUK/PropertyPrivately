
# Input Definitions [InputDefinition <- InputDefinitionInterface]

* Define properties
    - Name 
    - Model Property Path (Optional: Required if `name` is different to property path on model)
    - Model


<?php


interface InputDefinitionInterface 
{
    public function getDefinition();
}

class RegistrationInputDefinition implements InputDefinitionInterface 
{
    public function getDefinition()
    {
        return [
            'PropertyPrivately\SecurityBundle\Entity\User' => [
                'user_name' => 'username',
                'email',
                'pass_word' => 'password'
            ],
            'PropertyPrivately\SecurityBundle\Entity\Person' => [
                'name',
                'location',
            ],
        ];
    }
}
