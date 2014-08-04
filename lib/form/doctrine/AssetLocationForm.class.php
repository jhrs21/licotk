<?php

/**
 * AssetLocationForm is a wrapper for the embedded forms EmbeddedLocationForm
 *
 * @author Jacobo Martínez
 */
class AssetLocationForm extends sfForm
{
    protected $asset;
    
    protected $count;

    public function __construct(Asset $asset, $count = null)
    {
        $this->asset = $asset;
        
        $this->count = $count;

        parent::__construct();
    }
    
    public function configure() 
    {
        $i = 0;

        foreach ($this->asset->Location as $location)
        {
            $this->embedForm($i, new EmbeddedLocationForm($location));
            $i++;
        }

        $count = max($i, $this->count);
        
        for ($j = $i; $j < $count; $j++)
        {
            $this->embedForm($j, new EmbeddedLocationForm());
        }
    }
}

?>
