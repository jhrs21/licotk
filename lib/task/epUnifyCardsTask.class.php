<?php

/**
 * Send emails stored in a queue.
 *
 * @package    elperro
 * @subpackage task
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 */
class epUnifyCardsTask extends sfBaseTask {

    /**
     * @see sfTask
     */
    protected function configure() {
        $this->namespace = 'elperro';
        $this->name = 'unify-cards';

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'task'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('promo', null, sfCommandOption::PARAMETER_REQUIRED, 'The promo identifier'),
        ));

        $this->briefDescription = 'Unifies all the active, completed and exchanged cards that belongs to a promo';

        $this->detailedDescription = <<<EOF
The [elperro:unify-cards|INFO] unifies all the active, completed and exchanged cards that belongs to the given promo:

  [php symfony elperro:unify-cards --promo=10|INFO]
EOF;
    }

    public function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase(isset($options['connection']) ? $options['connection'] : null)->getConnection();
        $conn->setAttribute(Doctrine_Core::ATTR_AUTO_FREE_QUERY_OBJECTS, true );
        
        $promo = Doctrine_Core::getTable('Promo')->findOneBy('id', $options['promo']);

        $users = $this->usersQuery($options['promo'])->execute();
        $oCards = $this->getCards($options['promo']);
        $oTags = $this->getTags($options['promo']);

        $this->deleteCards($options['promo']);

        $i = 0;
        $length = count($oTags);

        foreach ($users as $user) {
            $card = new Card();
            $card->setPromoId($options['promo']);
            $card->setStatus('active');
            $card->setUserId($user);

            $card->save();
            
            $cardId = $card->getId();
            
            $card->free();
            unset($card);

            $tagsCollection = new Doctrine_Collection('Ticket');

            for ($j = $i; $j < $length; $j++) {
                if ($user == $oTags[$j]['user_id']) {
                    $oTags[$j]['card_id'] = $cardId;
                    $oTags[$j]['used'] = false;
                    $oTags[$j]['used_at'] = null;

                    $tag = new Ticket();
                    $tag->fromArray($oTags[$j]);

                    $tagsCollection->add($tag);

                    $i++;
                } else {
                    break;
                }
            }
            
            $tagsCollection->save();
            
            $tagsCollection->free(true);
            unset($tagsCollection);
        }

        $finalCards = $this->getCardsQuery($options['promo'])->count();
        $finalTags = $this->getTagsQuery($options['promo'])->count();

        $this->log(sprintf('---###  RECORDAR: UNA TARJETA CANJEADA EQUIVALE A EXCHANGED NO A REDEEMED  ###---'));
        $this->log(sprintf('Promo: %s', $promo->getName()));
        $this->log(sprintf('Usuarios: %s', count($users)));
        $this->log(sprintf('Tarjetas iniciales (activas, completadas y canjeadas): %s', count($oCards)));
        $this->log(sprintf('Tags iniciales (en tarjetas activas, completadas y canjeadas): %s', count($oTags)));
        $this->log(sprintf('Tarjetas finales: %s', $finalCards));
        $this->log(sprintf('Tags finales: %s', $finalTags));
    }
    
    protected function usersQuery($promo) {
        $q = Doctrine_Query::create()->from('sfGuardUser u');
        
        $alias = $q->getRootAlias();
        
        $q->addSelect('DISTINCT('.$alias.'.id) AS id');

        $q->leftJoin($alias . '.Cards c');
        
        $q->addWhere('c.promo_id = ?', $promo);

        $q->andWhereIn('c.status', array('active', 'complete', 'exchanged'));
        
        $q->orderBy($alias . '.id ASC');
        
        $q->setHydrationMode(Doctrine_Core::HYDRATE_SINGLE_SCALAR);

        return $q;
    }

    protected function getCards($promo) {
        return $this->getCardsQuery($promo)->execute();
    }

    protected function getCardsQuery($promo) {
        $cardTable = Doctrine::getTable('Card');

        $q = $cardTable->addByPromoQuery($promo, $cardTable->addByStatusNotQuery(array('expired', 'canceled', 'redeemed')));

        $q = $cardTable->addOrderByQuery('user_id', 'ASC', $q);

        $q->setHydrationMode(Doctrine_Core::HYDRATE_SINGLE_SCALAR);

        return $q;
    }

    protected function getTags($promo) {
        return $this->getTagsQuery($promo)->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }

    protected function getTagsQuery($promo) {
        $q = Doctrine_Query::create()->from('Ticket t');

        $alias = $q->getRootAlias();

        $q->select($alias . '.*');

        $q->leftJoin($alias . '.Card c');

        $q->addWhere($alias . '.promo_id = ?', $promo);
        
        $q->addWhere('c.promo_id = ?', $promo);

        $q->andWhereIn('c.status', array('active', 'complete', 'exchanged'));

        $q->orderBy($alias . '.user_id ASC');

        return $q;
    }

    protected function deleteCards($promo) {
        $q = Doctrine_Query::create()->delete('Card c');

        $alias = $q->getRootAlias();

        $q->addWhere('c.promo_id = ?', $promo);

        $q->andWhereIn('c.status', array('active', 'complete', 'exchanged'));

        return $q->execute(array(), Doctrine::HYDRATE_NONE);
    }

}
