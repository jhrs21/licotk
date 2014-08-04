<?php

/**
 * Description of epSwift_DoctrineSpool
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epSwift_DoctrineSpool extends Swift_DoctrineSpool {

    /**
     * Sends messages using the given transport instance. It retrieves the objects
     * from the database using 
     *
     * @param Swift_Transport $transport         A transport instance
     * @param string[]        &$failedRecipients An array of failures by-reference
     *
     * @return int The number of sent emails
     */
    public function flushQueue(Swift_Transport $transport, &$failedRecipients = null) {
        $table = Doctrine_Core::getTable($this->model);
        $query = $table->{$this->method}()->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        
        if ($this->getMessageLimit()) {
            $query->limit($this->getMessageLimit());
        }
        
        $objects = $query->execute();
        $retrieved = count($objects);

        if (!$transport->isStarted()) {
            $transport->start();
        }

        $success = 0;
        $fail = 0;
        $time = time();
        $ids = array();
        foreach ($objects as $object) {
            $message = unserialize($object[$this->column]);

            try {
                $success += $transport->send($message, $failedRecipients);
                array_unshift($ids, $object['id']);
            } catch (Exception $e) {
                $fail++;
            }
            
            // Free memory
            unset($object);

            if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit()) {
                break;
            }
        }

        $this->deleteSentMessages($ids);
        
        return array('success' => $success, 'fail' => $fail, 'retrieved' => $retrieved, 'run_time' => (time()-$time));
    }

    public function deleteSentMessages($ids = array()) {
        $deleted = Doctrine_Query::create()->delete()
                ->from($this->model)
                ->andWhereIn('id',$ids)
                ->execute();
        
        return $deleted;
    }

}
