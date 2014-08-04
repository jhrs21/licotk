<?php
/**
 * Description of epFeedbackAfterTagForm
 *
 * @author jacobo
 */
class epFeedbackForm extends FeedbackForm
{
    public function configure()
    {
        parent::configure();
        
        unset($this['id']);
        
        $this->widgetSchema['valoration'] = new sfWidgetFormChoice(
                array(
                    'choices' => array('2' => '&nbsp;','1' => '&nbsp;','0' => '&nbsp;'),
                    'expanded' => true,
                    'renderer_class' => 'epWidgetFormChoicesInLine',
                    'renderer_options' => array(
                            'label_separator' => '',
                            'label_class' => 'feedback-valoration-value',
                            'bvalidator_attrs' => array(
                                    'data-bvalidator' => 'required',
                                    'data-bvalidator-msg' => 'Indicanos como fue tu experiencia.'
                                ),
                            'class' => 'radio_list feedback-valoration'
                        )
                )
            );
        
        $this->widgetSchema['message']->setAttributes(array(
                    'maxlength' => 255,
                    'data-bvalidator' => 'minlength[3],maxlength[255]',
                    'data-bvalidator-msg' => 'Tu comentario debe contener entre 3 y 255 caracteres.'
                ));
        
        $this->validatorSchema['message']->setOption('min_length',3);
        $this->validatorSchema['message']->setMessage('min_length','Tu comentario debe contener entre 3 y 255 caracteres.');
        $this->validatorSchema['message']->setMessage('max_length','Tu comentario debe contener entre 3 y 255 caracteres.');
        
        $this->widgetSchema->setLabels(array(
                'message' => 'Comentario:',
                'valoration' => false
            ));
        
        $this->widgetSchema->setFormFormatterName('epWeb');
    }
    
    protected function embedSurveysForms() {        
        $user = $this->getObject()->getUser();
        $asset = $this->getObject()->getAsset();
        $surveys = $this->getObject()->getPromo()->getActiveSurveys();
        $via = $this->getOption('via', 'web');
        $action = $this->getObject()->getAction();
        
        $count = 0;
        foreach ($surveys as $key => $survey) {
            if (!($survey instanceof Survey)) {
                throw new InvalidArgumentException("You must pass a Survey collection/array as an option to this form!");
            }

            $surveyTaken = new SurveyApplication();

            $surveyTaken->setSurvey($survey);
            $surveyTaken->setUser($user);
            $surveyTaken->setVia($via);
            $surveyTaken->setAction($action);

            if ($asset) {
                $surveyTaken->setAsset($asset);
            }

            $surveyForm = new epSurveyForm($surveyTaken, array('items' => $survey->getOrderedItems(), 'user' => $user));

            $this->embedForm($count, $surveyForm);
            $count++;
        }
    }
}

?>
