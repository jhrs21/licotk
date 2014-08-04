<?php

/**
 * Description of EmbeddedPromoConditionValidatorSchema
 *
 * @author Jacobo Martínez
 */
class EmbeddedPromoPrizeValidatorSchema  extends sfValidatorSchema
{
    protected $validatorSchema;

    public function __construct(sfValidatorSchema $validatorSchema)
    {
        $this->validatorSchema = $validatorSchema;

        parent::__construct();
    }

    public function doClean($values)
    {
        if (!EmbeddedPromoPrizeForm::formValuesAreBlank($values))
        {
           return $this->validatorSchema->doClean($values);
        }

        return $values;
    }
}

?>
