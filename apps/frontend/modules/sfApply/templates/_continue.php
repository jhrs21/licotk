<p>
<?php // Support for sfDoctrineGuardPlugin's getReferer is nice if the user's session is ?>
<?php // still active when they confirm, as is often the case now ?>
<?php echo link_to(
        __("Continuar"), 
        $sf_user->isAuthenticated() ? 
            sfConfig::get('app_sfApplyPlugin_afterLogin', sfConfig::get('app_sfApplyPlugin_after', '@homepage')) 
            : 
            $sf_user->getReferer('@homepage')
    ) ?>
</p>
