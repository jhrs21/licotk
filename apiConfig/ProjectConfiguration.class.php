<?php

require_once dirname(__FILE__) . '/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration {

    public function setup() {
        $this->setWebDir($this->getRootDir().'/api');
        
        $this->enablePlugins('sfDoctrinePlugin');
        $this->enablePlugins('sfDoctrineGuardPlugin');
        $this->enablePlugins('sfDoctrineApplyPlugin');
        $this->enablePlugins('sdInteractiveChartPlugin');
        $this->enablePlugins('sfAdminThemejRollerPlugin');
        $this->enablePlugins('sfFlashMessagePlugin');
        $this->enablePlugins('sfJqueryReloadedPlugin');
        $this->enablePlugins('sfEasyGMapPlugin');
        $this->enablePlugins('sfThumbnailPlugin');

        // customize the mailer
        $this->dispatcher->connect('mailer.configure', array($this, 'configureMailer'));
    }

    public function configureMailer(sfEvent $event) {
        $mailer = $event->getSubject();
        $transport = $mailer->getRealtimeTransport();

        $transport->registerPlugin(new Swift_Plugins_ThrottlerPlugin(100, Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE));

        $transport->registerPlugin(new Swift_Plugins_AntiFloodPlugin(30));
    }

}
