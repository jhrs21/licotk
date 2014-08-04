<?php

class helloWorldTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = '';
    $this->name             = 'helloWorld';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [helloWorld|INFO] task does things.
Call it with:

  [php symfony helloWorld|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
      echo "Hello World!!!"."\n";
  }
}
