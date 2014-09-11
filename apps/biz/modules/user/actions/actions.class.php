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

      $this->pager = new sfDoctrinePager('sfGuardUser', 20);
      $this->users = [];      
      foreach ($subcriptions as $s){
          $pockets = $s->getUser()->getPockets();
          array_push($this->users, 
                  array(
                      "fullname" => $s->getUser()->getFullname(),
                      "email" => $s->getUser()->getEmailAddress(),
                      "tags" => $pockets[0]->getTotalTags(),
                      "level" => $s->getUser()->getLicotecaLevel(),
                      "id" => $s->getUser()->getAlphaId()
                  ) ); 
      }
    }
    
    public function executeChangeLevel(sfWebRequest $request){
        $params = $request->getGetParameters();
        
    }
}
