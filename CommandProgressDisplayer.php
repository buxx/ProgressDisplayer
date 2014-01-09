<?php

require_once 'ProgressDisplayer.php';

class CommandProgressDisplayer extends ProgressDisplayer
{
  protected $break = "\n";
  protected $sub_space = "...";
  protected $sub_space_model = "%s";
  
  protected $color_model = "\e[%s%s\e[0m";
  protected $colors = array(
    'std' => '1;34m',
    'success' => '1;32m',
    'error' => '1;31m',
    'info' => '1;36m',
  );
}