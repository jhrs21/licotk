<?php

/**
 * AssetFiscalInfoForm is a wrapper for the embedded forms EmbeddedFiscalInfoForm
 *
 * @author Jacobo Martínez
 */
class AssetFiscalInfoForm extends sfForm
{
    protected $asset;

    public function __construct(Asset $asset)
    {
        $this->asset = $asset;

        parent::__construct();
    }

    public function configure() 
    {
        if ($this->asset->getLocation())
        {
            $this->embedForm('location', new EmbeddedFiscalInfoForm($this->asset->getFiscalInfo()));
        }
        else
        {
            $this->embedForm('location', new EmbeddedFiscalInfoForm());
        }
    }
}

?>
