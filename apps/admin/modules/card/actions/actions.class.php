<?php

/**
 * card actions.
 *
 * @package    elperro
 * @subpackage card
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cardActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->form = new epUnifyPromoCardsForm();
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            
            if ($this->form->isValid()) {                
                $this->promo = $this->form->getValue('promo');
                $users = $this->form->getValue('users');
                $oCards = $this->getCards($this->promo->getId());
                $oTags  = $this->getTags($this->promo->getId());
                
                $this->deleteCards($this->promo->getId());
                
                $i = 0;
                $length = count($oTags);
                
                foreach ($users as $user) {
                    $card = new Card();
                    $card->setPromoId($this->promo->getId());
                    $card->setStatus('active');
                    $card->setUserId($user);
                    
                    $card->save();
                    
                    $tagsCollection = new Doctrine_Collection('Ticket');
                    
                    for($j = $i; $j < $length; $j++){
                        if ($user == $oTags[$j]['user_id']) {
                            $oTags[$j]['card_id'] = $card->getId();
                            
                            $tag = new Ticket();
                            $tag->fromArray($oTags[$j]);
                            
                            $tagsCollection->add($tag);
                            
                            $i++;
                        } else {
                            $tagsCollection->save();
                            $tagsCollection->free(true);
                            
                            break;
                        }
                    }
                    
                    $card->free();
                }
                
                $this->users = count($users);
                $this->oCards = count($oCards);
                $this->oTags  = count($oTags);
                
                $this->fCards = $this->getCardsQuery($this->promo->getId())->count();
                $this->fTags  = $this->getTagsQuery($this->promo->getId())->count();
                
                return 'Done';
            }
        }
    }
    
    protected function getCards($promo) {
        return $this->getCardsQuery($promo)->execute();
    }
    
    protected function getCardsQuery($promo) {
        $cardTable = Doctrine::getTable('Card');
        
        $q = $cardTable->addByPromoQuery($promo, $cardTable->addByStatusNotQuery(array('expired','canceled','exchanged','redeemed')));
        
        $q = $cardTable->addOrderByQuery('user_id', 'ASC', $q);
        
        $q->setHydrationMode(Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        
        return $q;
    }
    
    protected function getTags($promo) {
        return $this->getTagsQuery($promo)->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    protected function getTagsQuery($promo) {
        $q = Doctrine_Query::create()->from('Ticket t');
        
        $alias = $q->getRootAlias();
        
        $q->select($alias . '.*');

        $q->leftJoin($alias . '.Card c');
        
        $q->addWhere('c.promo_id = ?', $promo);

        $q->andWhereIn('c.status', array('active', 'complete'));
        
        $q->orderBy($alias . '.user_id ASC');

        return $q;
    }
    
    protected function deleteCards($promo) {
        $q = Doctrine_Query::create()->delete('Card c');
        
        $alias = $q->getRootAlias();
        
        $q->addWhere('c.promo_id = ?', $promo);

        $q->andWhereIn('c.status', array('active', 'complete'));

        return $q->execute(array(),Doctrine::HYDRATE_NONE);
    }
}
