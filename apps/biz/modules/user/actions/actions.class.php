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
      
      $this->users = [];      
      foreach ($subcriptions as $s){
          array_push($this->users, 
                  array( 
                      "email" => $s->getUser()->getEmailAddress(),
                      "tags" => $s->getUser()->getPockets()[0]->getTotalTags(),
                      "level" => $s->getUser()->getLicotecaLevel()
                  ) ); 
      }
    }
}
