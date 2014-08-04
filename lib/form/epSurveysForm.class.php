<?php

/**
 * Description of epSurveysForm
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epSurveysForm extends sfFormDoctrine {

    public function configure() {
        parent::configure();

        if (!($this->getOption('user') instanceof sfGuardUser)) {
            throw new InvalidArgumentException("You must pass an User object as an option to this form!");
        } else {
            $user = $this->getOption('user');
        }

        if (!($this->getOption('surveys') instanceof Doctrine_Collection)) {
            throw new InvalidArgumentException("You must pass a Survey collection as an option to this form!");
        } else {
            $surveys = $this->getOption('surveys');
        }

        if (!$this->getOption('action')) {
            throw new InvalidArgumentException("You must pass an action string as an option to this form!");
        } else {
            $action = $this->getOption('action');
        }

        if (!$this->getOption('via')) {
            throw new InvalidArgumentException("You must pass a via string as an option to this form!");
        } else {
            $via = $this->getOption('via');
        }
        
        $asset = false;
        if ($this->getOption('asset') && !($this->getOption('asset') instanceof Asset)) {
            throw new InvalidArgumentException("You must pass an Asset object as an option to this form!");
        } else {
            $asset = $this->getOption('asset');
        }
        
        //$usesAlphaId = $this->getOption('usesAlphaId', false);

        //$count = 0;
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
            //$surveyForm = new epSurveyForm($surveyTaken, array('items' => $survey->getOrderedItems(), 'user' => $user, 'usesAlphaId' => $usesAlphaId));

            $this->embedForm($survey->getAlphaId(), $surveyForm);
            //$this->embedForm(($usesAlphaId ? $survey->getAlphaId() : $count), $surveyForm);
            //$count++;
        }

        if (!$this->getOption('embbed', false)) {
            $this->widgetSchema->setNameFormat('surveys[%s]');
        }

        $this->widgetSchema->setFormFormatterName('epWeb');
        
        if ($this->getOption('disableCSRFProtection', false)) {
            $this->disableCSRFProtection();
        }
    }

    public function getModelName() {
        return 'SurveyApplication';
    }

    /**
     * Updates and saves the current embedded forms.
     *
     * @param mixed $con An optional connection object
     */
    protected function doSave($con = null) {
        if (null === $con) {
            $con = $this->getConnection();
        }

        $this->updateObjectEmbeddedForms($this->values);

        // embedded forms
        $this->saveEmbeddedForms($con);
    }

}