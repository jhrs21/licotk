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
        $user = $this->getUser()->getGuardUser();

        $promos = $user->getAffiliate()->getPromos();

        $assets = $user->getAffiliate()->getAssets();

        $this->assetsIds = $assets->getPrimaryKeys();

        $this->pager = new sfDoctrinePager('Survey', sfConfig::get('app_ep_surveys_per_page', 20));

        $this->pager->setQuery(Doctrine::getTable('Survey')->getPromosSurveysQuery($promos->getPrimaryKeys(), true, false));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();

        if ($request->isXmlHttpRequest()) {
            return $this->renderPartial('list', array('pager' => $this->pager, 'assetsIds' => $this->assetsIds));
        }
    }

    public function executeResults(sfWebRequest $request) {
        $user = $this->getUser()->getGuardUser();
        
        $this->survey = $this->getRoute()->getObject();
        $this->assetsIds = $request->getParameter('asset_id', array());
        
        $table = Doctrine::getTable('SurveyApplication');
        
        $query = $table->addByAssetsQuery($this->assetsIds, $table->addBySurveyIdQuery($this->survey->getId(), $this->getBaseQuery()));
        
        $this->result = array();
        $this->result['data'] = json_encode($query->execute(array(), Doctrine::HYDRATE_ARRAY_SHALLOW));
        $this->result['survey'] = json_encode($this->survey->asArray());
        //var_dump($this->result);
        $this->form = new epSurveyApplicationFormFilter(
                    array('survey_id' => $this->survey->getId(),'asset_id' => $this->assetsIds), 
                    array('affiliate' => $user->getAffiliateId())
                );
    }

    public function executeFilterResults(sfWebRequest $request) {
        $this->getResponse()->setContentType('application/json');
        
        $result = array('success' => 0);
        
        $this->survey = $this->getRoute()->getObject();
        
        $user = $this->getUser()->getGuardUser();

        $this->form = new epSurveyApplicationFormFilter(array(), array('affiliate' => $user->getAffiliateId(), 'query' => $this->getBaseQuery()));
        
        $this->form->bind($request->getParameter($this->form->getName()));
        
        $result['html'] = $this->getPartial('survey/surveyResultsFilter', array('form' => $this->form, 'survey' => $this->survey));
        
        if ($this->form->isValid()) {
            $query = $this->form->getQuery();
        
            $result['success'] = 1;
            $result['data'] = $query->execute(array(), Doctrine::HYDRATE_ARRAY_SHALLOW);
            $result['survey'] = $this->survey->asArray();
        }
        
        return $this->renderText(json_encode($result));
    }
    
    public function executeData(sfWebRequest $request) {
        $survey = $this->getRoute()->getObject();

        $assetsIds = $request->getParameter('asset_id', array());

        $itemsNames = $survey->getItemsNames(true);
        $itemsOrder = array_keys($itemsNames);

        $filename = 'datos_' . Util::slugify($survey->getName()) . '.csv';
        $path = sfConfig::get('sf_web_dir') . '/downloads/' . $filename;

        $file = fopen($path, 'w');

        fputcsv($file, array_values($itemsNames));

        $applications = $survey->getApplicationsForAssets($assetsIds);

        foreach ($applications as $application) {
            fputcsv($file, $application->getOrderedAnswers($itemsOrder));
        }

        fclose($file);

        $this->getResponse()->clearHttpHeaders();
        $this->getResponse()->setStatusCode(200);
        $this->getResponse()->setContentType('text/csv; charset=utf-8; encoding=utf-8');
        $this->getResponse()->setHttpHeader('Pragma', 'public'); //optional cache header
        $this->getResponse()->setHttpHeader('Expires', 0); //optional cache header
        $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=' . $filename);
        $this->getResponse()->setHttpHeader('Content-Transfer-Encoding', 'binary');
        $this->getResponse()->setHttpHeader('Content-Length', filesize($path));

        return $this->renderText(chr(239) . chr(187) . chr(191) . file_get_contents($path)); //LOS 3 PRIMEROS CARACTERES (chr(239), chr(187), chr(191)) son el valor BOM para UTF-8
    }

    protected function calcuteTotalUsersByGender($data) {
        $results = array('male' => 0, 'female' => 0, 'total' => 0);

        foreach ($data as $row) {
            $results[$row['gender']] += $row['quantity'];
        }

        $results['total'] = $results['male'] + $results['female'];

        return $results;
    }

    protected function calcuteAgeGroups($data) {
        $categories = array('< 18', '18 - 24', '25 - 34', '35 - 44', '45 - 54', '55 - 64', '> 65');

        $results = array();
        foreach ($categories as $key => $category) {
            $results[$key] = array('range' => $category, 'male' => 0, 'female' => 0);
        }

        foreach ($data as $row) {
            if ($row['age'] < 18) {
                $results[0][$row['gender']] += $row['quantity'];
            } elseif ($row['age'] < 25) {
                $results[1][$row['gender']] += $row['quantity'];
            } elseif ($row['age'] < 35) {
                $results[2][$row['gender']] += $row['quantity'];
            } elseif ($row['age'] < 45) {
                $results[3][$row['gender']] += $row['quantity'];
            } elseif ($row['age'] < 55) {
                $results[4][$row['gender']] += $row['quantity'];
            } elseif ($row['age'] < 65) {
                $results[5][$row['gender']] += $row['quantity'];
            } elseif ($row['age'] >= 65) {
                $results[6][$row['gender']] += $row['quantity'];
            }
        }

        return $results;
    }
    
    public function getBaseQuery() {        
        $query = Doctrine_Query::create()->from('SurveyApplication sa')->select('sa.id as application, sa.created_at, sa.asset_id as asset');
        
        $query->addSelect('u.id as user');
        $query->addSelect('FLOOR(DATEDIFF(NOW(),up.birthdate)/365.2425) as age');
        $query->addSelect('up.gender as gender');
        $query->addSelect('a.survey_item_id as item,a.answer as answer');
        
        $query->leftJoin('sa.User u ON u.id = sa.user_id');
        $query->leftJoin('u.UserProfile up ON up.user_id = u.id');
        $query->leftJoin('sa.Answers a');
        
        $query->addOrderBy('sa.created_at DESC');
        $query->addOrderBy('gender ASC');
        $query->addOrderBy('age ASC');
        
        return $query;
    }

    protected function generateCsvFile($data, $headers = array()) {
        // create a file pointer connected to the output stream
        if (is_null($file)) {
            $file = fopen('php://output', 'w');
        }

        // output the column headings
        if (count($headers)) {
            fputcsv($output, $headers);
        }

        // loop over the rows, outputting them
        while ($row = mysql_fetch_assoc($rows))
            fputcsv($output, $row);
    }
}
