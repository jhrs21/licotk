<?php

/**
 * Description of epSurveyItemAnswer
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epSurveyItemAnswerForm extends SurveyItemAnswerForm {

    public function configure() {
        parent::configure();
        
        unset($this['id'],$this['survey_application_id'],$this['survey_id'],$this['user_id'],$this['survey_item_id']);
        
        if (!($this->getOption('item') instanceof SurveyItem)) {
            throw new InvalidArgumentException("You must pass a SurveyItem collection/array as an option to this form!");
        } else {
            $item = $this->getOption('item');
        }
        
        $usesAlphaId = $this->getOption('usesAlphaId', true);
        
        $this->setAnswerWidgetAndValidator($item, $usesAlphaId);
        
        $this->widgetSchema->setFormFormatterName('epWeb');
    }

    protected function setAnswerWidgetAndValidator(SurveyItem $item, $usesAlphaId = true) {
        switch ($item->getItemType()) {
            case 'text':
                $this->widgetSchema['answer'] = new sfWidgetFormInputText(
                            array(),
                            array(
                                'data-bvalidator' => $item->getIsRequired() ? 'required' : '', 
                                'data-bvalidator-msg' => 'Por favor, completa este campo.'
                            )
                        );
                /* Se mantiene el validator que fue generado por defecto */
                $this->validatorSchema['answer']->setOption('required',$item->getIsRequired());
                $this->validatorSchema['answer']->setMessage('required', 'Por favor, completa este campo.');
                break;
            case 'date':
                $this->widgetSchema['answer'] = new sfWidgetFormInputText(
                            array(),
                            array(
                                'class' => 'uses-datepicker',
                                'data-bvalidator' => $item->getIsRequired() ? 'date[dd/mm/yyyy],required' : 'date[dd/mm/yyyy]', 
                                'data-bvalidator-msg' => 'Por favor, introduce una fecha en formato dd/mm/aaaa'
                            )
                        );
                $this->validatorSchema['answer'] = new sfValidatorDate(
                            array(
                                'required' => $item->getIsRequired(),
                                'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~'
                            ),
                            array(
                                'required'   => 'Por favor, introduce una fecha en formato "dd/mm/aaaa"', 
                                'invalid'    => 'La fecha introducida es inválida.', 
                                'bad_format' => 'La fecha "%value%" no está en el formato "dd/mm/aaaa".')
                        );
                break;
            case 'simple_selection':
                $table = Doctrine::getTable('SurveyItemOption');
                $this->widgetSchema['answer'] = new sfWidgetFormDoctrineChoice(
                            array(
                                'model'    => 'SurveyItemOption', 
                                'expanded' => true, 
                                'multiple' => false,
                                'method'   => 'asWidgetChoice',
                                'query'    => $table->getByItemQuery($item->getId()),
                                'key_method' => 'getAlphaId',
                                //'key_method' => ($usesAlphaId ? 'getAlphaId' : 'getPrimaryKey'),
                                'renderer_class' => 'epWidgetFormSurveyRadioButton',
                                'renderer_options' => array(
                                    'first_radio_attributes' => array(
                                        'data-bvalidator' => $item->getIsRequired() ? 'required' : '', 
                                        'data-bvalidator-msg' => 'Por favor, selecciona una opción.'
                                    ),
                                )
                            ),
                            array()
                        );
                $this->validatorSchema['answer'] = new sfValidatorDoctrineChoice(
                            array(
                                'model'    => 'SurveyItemOption', 
                                'column'   => 'alpha_id',
                                'query'    => $table->getByItemQuery($item->getId()),
                                //'column' => ($usesAlphaId ? 'alpha_id' : null),
                                'required' => $item->getIsRequired(), 
                                'multiple' => false
                            ),
                            array('required' => 'Por favor, selecciona una opción.')
                        );
                break;
            case 'multiple_selection':
                $table = Doctrine::getTable('SurveyItemOption');
                $this->widgetSchema['answer'] = new sfWidgetFormDoctrineChoice(
                            array(
                                'model'    => 'SurveyItemOption', 
                                'expanded' => true, 
                                'multiple' => true,
                                'method'   => 'asWidgetChoice',
                                'query'    => $table->getByItemQuery($item->getId()),
                                'key_method' => 'getAlphaId',
                                //'key_method' => ($usesAlphaId ? 'getAlphaId' : 'getPrimaryKey'),
                                'renderer_class' => 'epWidgetFormSurveyCheckbox',
                                'renderer_options' => array(
                                    'first_checkbox_attributes' => array(
                                        'data-bvalidator' => $item->getIsRequired() ? 'required' : '', 
                                        'data-bvalidator-msg' => 'Por favor, selecciona al menos una opción.'
                                    ),
                                )
                            ),
                            array()
                        );
                $this->validatorSchema['answer'] = new epValidatorSurveyMultipleChoice(
                            array(
                                'model'     => 'SurveyItemOption',
                                'column'    => 'alpha_id',
                                'query'     => $table->getByItemQuery($item->getId()),
                                //'column' => ($usesAlphaId ? 'alpha_id' : null),
                                'required'  => $item->getIsRequired(),
                                'multiple'  => true
                            ),
                            array('required' => 'Por favor, selecciona al menos una opción.')
                        );
                break;
            default:
                break;
        }

        $this->widgetSchema['answer']->setLabel($item->getLabel());

        if ($item->getHelp()) {
            $this->widgetSchema->setHelp('answer', $item->getHelp());
        }
        
        $this->widgetSchema->setFormFormatterName('epWeb');
    }
}