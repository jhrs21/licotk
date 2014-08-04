<?php

/**
 * Description of EmbeddedPromoConditionValidatorSchema
 *
 * @author Jacobo Martínez
 */
class EmbeddedPromoTermValidatorSchema  extends sfValidatorSchema
{
    protected $validatorSchema;

    public function __construct(sfValidatorSchema $validatorSchema)
    {
        $this->validatorSchema = $validatorSchema;

        parent::__construct();
    }

    public function doClean($values)
    {
        if (!EmbeddedPromoTermForm::formValuesAreBlank($values))
        {
           return $this->validatorSchema->doClean($values);
        }

        return $values;
    }
}

?>
