<?php

/**
 * user actions.
 *
 * @package    elperro
 * @subpackage user
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request){
      $user = $this->getUser()->getGuardUser();
      $affiliate = $user->getAffiliate();
      $subcriptions = $affiliate->getSubcriptions();
      $temp = Doctrine::getTable('UserLicotecaUserLevel')->findAll();
      $this->pager = new sfDoctrinePager('sfGuardUser', 20);
      $this->users = [];      
      foreach ($temp as $s){
      //foreach ($subcriptions as $s){
            $pockets = $s->getUser()->getPockets();
            array_push($this->users, 
                    array(
                        "fullname" => $s->getUser()->getFullname(),
                        "email" => $s->getUser()->getEmailAddress(),
                        "tags" => $pockets[0]->getTotalTags(),
                        "level" => $s->getUser()->getLicotecaLevel(),
                        "id" => $s->getUser()->getAlphaId()
                    ));
      }
    }
    
    public function executeChangeLevel(sfWebRequest $request){
        $params = $request->getGetParameters();
        $this->user = null;
        $user_level = "";
        if (array_key_exists('user', $params) && $params['user'] != ''){
            $this->user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($params['user']);
            if (!$this->user){
                $this->forward404();
            }
        }else{
            $this->forward404();
        }
        $user_level = Doctrine::getTable('UserLicotecaUserLevel')->findOneByUserId($this->user->getId())->getLevelId();
                
        $this->form = new epUserLicotecaLevelForm(array(), array('level' => $user_level));
        
        if ($request->isMethod('post'))
        {
            $this->form->bind($request->getParameter($this->form->getName()));
            $formValues = $this->form->getValues();
            $obj = Doctrine::getTable('UserLicotecaUserLevel')->findOneByUserId($this->user->getId());
            $obj->setLevelId($formValues['level']);
            $obj->save();
            $this->redirect('user_list');
        }
    }
    
    public function executeManageLevels(sfWebRequest $request){
        $levels = Doctrine::getTable('LicotecaUserLevel')->findAll();
        $this->result = array();
        foreach ($levels as $level) {
            array_push($this->result, ['level' => $level->getName(), 'bottom' => $level->getBottom(), 'top' => $level->getTop()]);
        }
        $this->form = new epLicotecaLevelForm();
        
        if ($request->isMethod('post'))
        {
            $this->form->bind($request->getParameter($this->form->getName()));
            $formValues = $this->form->getValues();
            $level_object = Doctrine::getTable('LicotecaUserLevel')->find($formValues['level']);
            $level_object->setBottom($formValues['bottom']);
            $level_object->setTop($formValues['top']);
            $level_object->save();
            $this->redirect('change_user_level');
        }
    }
}
