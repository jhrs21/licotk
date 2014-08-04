<?php

/**
 * Send emails stored in a queue.
 *
 * @package    elperro
 * @subpackage task
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 */
class epSendEmailsTask extends sfBaseTask {

    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('message-limit', null, sfCommandOption::PARAMETER_OPTIONAL, 'The maximum number of messages to send', 0),
            new sfCommandOption('time-limit', null, sfCommandOption::PARAMETER_OPTIONAL, 'The time limit for sending messages (in seconds)', 0),
        ));

        $this->namespace = 'elperro';
        $this->name = 'send-emails';

        $this->briefDescription = 'Sends emails stored in a queue';

        $this->detailedDescription = <<<EOF
The [elperro:send-emails|INFO] sends emails stored in a queue:

  [php symfony elperro:send-emails|INFO]

You can limit the number of messages to send:

  [php symfony elperro:send-emails --message-limit=10|INFO]

Or limit to time (in seconds):

  [php symfony elperro:send-emails --time-limit=10|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);

        $spool = $this->getMailer()->getSpool();
        $spool->setMessageLimit($options['message-limit']);
        $spool->setTimeLimit($options['time-limit']);

        $result = $this->getMailer()->flushQueue();

        $format = 'Retrieved messages: %s Sent emails: %s Failed attempts: %s Run Time (seg): %s ';
        $this->log(sprintf($format, $result['retrieved'], $result['success'], $result['fail'], $result['run_time']));
    }

}
