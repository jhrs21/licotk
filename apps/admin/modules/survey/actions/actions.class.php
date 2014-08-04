<?php

/**
 * survey actions.
 *
 * @package    elperro
 * @subpackage survey
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class surveyActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->surveys = Doctrine_Core::getTable('Survey')->createQuery('a')->execute();
        
        $this->setLayout('layout_blueprint');
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new SurveyForm(null, array('routing' => $this->getContext()->getRouting()));
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->processForm($request, $this->form);
        }
    }

    public function executeEdit(sfWebRequest $request) {
        $this->survey = $this->getRoute()->getObject();
        
        $this->form = new SurveyForm($this->survey, array('routing' => $this->getContext()->getRouting()));
        
        if ($request->isMethod(sfWebRequest::PUT)) {
            $this->processForm($request, $this->form);
        }
    }

    public function executeShow(sfWebRequest $request) {
        $this->survey = $this->getRoute()->getObject();
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $survey = $form->save();

            $this->redirect('survey/show?id=' . $survey->getId());
        }
    }
    
    public function executeAddItem(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());

        $count = $request->getParameter('count', 0);
        $survey = false;
        
        if ($request->hasParameter('survey')) {
            $survey = Doctrine::getTable('Survey')->findOneById($request->getParameter('survey'));
        }

        if ($survey) {
            $form = new SurveyForm($survey, array('routing' => $this->getContext()->getRouting()));
        }
        else {
            $form = new SurveyForm(null, array('routing' => $this->getContext()->getRouting()));
        }
        
        $form->addItem($count);

        $this->item = $form['items'][$count];
        
        return $this->renderPartial('itemForm',array('key' => $count, 'item' => $this->item));
    }
    
    public function executeAddItemOption(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());

        $item = $request->getParameter('item', 0);
        $option = $request->getParameter('option', 0);;
        
        $form = new SurveyForm(null, array('routing' => $this->getContext()->getRouting()));
        
        $form->addOption($item, $option);

        $optionForm = $form['items'][$item]['options'][$option];

        return $this->renderPartial('itemOptionForm',array('key' => $option, 'item' => $this->$item, 'option' => $optionForm));
    }
    
    public function executeGetItemOptions(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());

        $count = $request->getParameter('count', 0);
        $survey = false;
        
        if ($request->hasParameter('survey')) {
            $survey = Doctrine::getTable('Survey')->findOneById($request->getParameter('survey'));
        }
        
        if ($request->hasParameter('item')) {
            $item = Doctrine::getTable('SurveyItem')->findOneById($request->getParameter('item'));
        }

        if ($survey) {
            $form = new SurveyForm($survey, array('routing' => $this->getContext()->getRouting()));
        }
        else {
            $form = new SurveyForm(null, array('routing' => $this->getContext()->getRouting()));
        }
        
        $form->getEmbeddedForm('items')->getEmbeddedForm($item)->addOption($count);

        $this->option = $form['items'][$item]['options'][$count];

        $this->setLayout(false);
    }
    
    /**
     * Depending on the route model option it can delete any object.
     * 
     * @param sfWebRequest $request
     * @return type 
     */
    public function executeDeleteSurveyElement(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());

        $this->getRoute()->getObject()->delete();

        $this->getResponse()->setHeaderOnly(true);
        $this->getResponse()->setStatusCode(200);
        return sfView::NONE;
    }
    
    public function executeResults(sfWebRequest $request) {
        $this->survey = $this->getRoute()->getObject();
        
        $table = Doctrine::getTable('SurveyApplication');
        
        $query = $table->addBySurveyIdQuery($this->survey->getId(), $this->getBaseQuery());
        
        $this->result = array();
        $this->result['applications'] = $table->addBySurveyIdQuery($this->survey->getId())->count();
        $this->result['data'] = json_encode($query->execute(array(), Doctrine::HYDRATE_ARRAY_SHALLOW));
        $this->result['survey'] = json_encode($this->survey->asArray(true));

        $this->form = new epSurveyResultFormFilter(
                    array('survey_id' => $this->survey->getId()), 
                    array('survey' => $this->survey)
                );
    }

    public function executeFilterResults(sfWebRequest $request) {
        $this->survey = $this->getRoute()->getObject();
        
        $this->getResponse()->setContentType('application/json');
        
        $result = array('success' => 0);
        
        $user = $this->getUser()->getGuardUser();

        $this->form = new epSurveyResultFormFilter(array(), array('survey' => $this->survey, 'query' => $this->getBaseQuery()));
        
        $this->form->bind($request->getParameter($this->form->getName()));
        
        $result['html'] = $this->getPartial('survey/surveyResultsFilter', array('form' => $this->form, 'survey' => $this->survey));
        
        if ($this->form->isValid()) {
            $query = $this->form->getQuery();
            $table = Doctrine::getTable('SurveyApplication');
        
            $result['success'] = 1;
            $result['applications'] = $table->addBySurveyIdQuery($this->survey->getId())->count();
            $result['data'] = $query->execute(array(), Doctrine::HYDRATE_ARRAY_SHALLOW);
            $result['survey'] = $this->survey->asArray(true);
        }
        
        return $this->renderText(json_encode($result));
    }
    
    public function getBaseQuery() {        
        $query = Doctrine_Query::create()->from('SurveyApplication sa')->select('sa.id as application, sa.created_at, sa.asset_id as asset');
        
        $query->addSelect('u.id as user');
        $query->addSelect('FLOOR(DATEDIFF(NOW(),up.birthdate)/365.2425) as age');
        $query->addSelect('up.gender as gender');
        $query->addSelect('a.answer as answer');
        $query->addSelect('i.alpha_id as item,');
        
        $query->leftJoin('sa.User u ON u.id = sa.user_id');
        $query->leftJoin('u.UserProfile up ON up.user_id = u.id');
        $query->leftJoin('sa.Answers a');
        $query->leftJoin('a.Item i');
        
        $query->addWhere('u.pre_registered = 0');
        
        $query->addOrderBy('sa.created_at DESC');
        $query->addOrderBy('gender ASC');
        $query->addOrderBy('age ASC');
        
        return $query;
    }
}
