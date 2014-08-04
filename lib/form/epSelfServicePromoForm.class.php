<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class epSelfServicePromoForm extends BasePromoForm {

    public function configure() {
        $this->useFields(array(
            'name', 'description', 'max_uses',
            'max_daily_tags', 'starts_at', 'ends_at',
            'begins_at', 'expires_at'
        ));

        $this->widgetSchema->setHelp('max_uses', 'Cero (0) es equivalente a ilimitado.');

        $this->widgetSchema->setHelp('max_daily_tags', 'Cero (0) es equivalente a ilimitado.');

        $this->widgetSchema['starts_at'] = new sfWidgetFormInput(array(), array('class' => 'datepicker'));

        $this->widgetSchema['ends_at'] = new sfWidgetFormInput(array(), array('class' => 'datepicker'));

        $this->widgetSchema['begins_at'] = new sfWidgetFormInput(array(), array('class' => 'datepicker'));

        $this->widgetSchema['expires_at'] = new sfWidgetFormInput(array(), array('class' => 'datepicker'));

        $this->embedForm('prizes', new PromoPrizesForm($this->object));

        $this->embedForm('terms', new PromoTermsForm($this->object, null, true));

        $this->validatorSchema->setPostValidator(new sfValidatorAnd(
                        array(
                            new sfValidatorSchemaCompare(
                                    'starts_at',
                                    sfValidatorSchemaCompare::LESS_THAN_EQUAL,
                                    'ends_at',
                                    array(),
                                    array('invalid' => 'La fecha de inicio para acumular Tags no puede ser mayor que la fecha de fin.')
                            ),
                            new sfValidatorSchemaCompare(
                                    'begins_at',
                                    sfValidatorSchemaCompare::LESS_THAN_EQUAL,
                                    'expires_at',
                                    array(),
                                    array('invalid' => 'La fecha de inicio para canjear Premios no puede ser mayor que la fecha de fin para el canje.')
                            ),
                            new sfValidatorSchemaCompare(
                                    'begins_at',
                                    sfValidatorSchemaCompare::GREATER_THAN_EQUAL,
                                    'starts_at',
                                    array(),
                                    array('invalid' => 'La fecha de inicio para el canje de Premios debe ser mayor o igual la fecha de inicio para acumular Tags.')
                            ),
                            new sfValidatorSchemaCompare(
                                    'expires_at',
                                    sfValidatorSchemaCompare::GREATER_THAN_EQUAL,
                                    'ends_at',
                                    array(),
                                    array('invalid' => 'La fecha de expiración de los Premios debe ser mayor o igual la fecha de fin para acumular Tags.')
                            ),
                        )
        ));

        $this->widgetSchema->setLabels(array(
            'name' => 'Nombre de la promoción',
            'description' => 'Descripción',
            'max_uses' => 'Cantidad de premios posibles por usuario',
            'max_daily_tags' => 'Cantidad de "tags" diarios',
            'starts_at' => 'Fecha de inicio de promoción',
            'ends_at' => 'Fecha final de promoción',
            'begins_at' => 'Fecha de inicio del canje',
            'expires_at' => 'Fecha final del canje',
            'prizes' => 'Premios',
            'terms' => 'Condiciones'
        ));

        $this->widgetSchema->setFormFormatterName('epWeb');
    }

    public function setNumPrizes($numPrizes) {
        $this->embedForm('prizes', new PromoPrizesForm($this->object, $numPrizes));
    }

    public function setNumTerms($numTerms) {
        $this->embedForm('terms', new PromoTermsForm($this->object, $numTerms));
    }

    public function updateObjectEmbeddedForms($values, $forms = null) {
        if (is_array($forms)) {
            foreach ($forms as $key => $form) {
                if ($form instanceof EmbeddedPromoPrizeForm) {
                    $formValues = isset($values[$key]) ? $values[$key] : array();

                    if (EmbeddedPromoPrizeForm::formValuesAreBlank($formValues)) {
                        if ($id = $form->getObject()->getId()) {
                            $this->object->unlink('Prizes', $id);

                            $form->getObject()->delete();
                        }

                        unset($forms[$key]);
                    }
                } else if ($form instanceof EmbeddedPromoTermForm) {
                    $formValues = isset($values[$key]) ? $values[$key] : array();

                    if (EmbeddedPromoTermForm::formValuesAreBlank($formValues)) {
                        if ($id = $form->getObject()->getId()) {
                            $this->object->unlink('Terms', $id);

                            $form->getObject()->delete();
                        }

                        unset($forms[$key]);
                    }
                }
            }
        }

        return parent::updateObjectEmbeddedForms($values, $forms);
    }

    public function saveEmbeddedForms($con = null, $forms = null) {
        if (is_array($forms)) {
            foreach ($forms as $key => $form) {
                if ($form instanceof EmbeddedPromoPrizeForm || $form instanceof EmbeddedPromoTermForm) {
                    if ($form->getObject()->isModified()) {
                        $form->getObject()->Promo = $this->object;
                    } else {
                        unset($forms[$key]);
                    }
                }
            }
        }

        return parent::saveEmbeddedForms($con, $forms);
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        if (isset($taintedValues['prizes']) && is_array($taintedValues['prizes'])) {
            $this->setNumPrizes(count($taintedValues['prizes']));
        }

        if (isset($taintedValues['terms']) && is_array($taintedValues['terms'])) {
            $this->setNumTerms(count($taintedValues['terms']));
        }

        return parent::bind($taintedValues, $taintedFiles);
    }

}
