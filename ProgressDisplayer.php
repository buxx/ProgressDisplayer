<?php

/**
 * 
 * Le header doit être spécifié pour que le rendu soit progressif !
 */
class ProgressDisplayer
{
  const STANDART = 'std';
  const SUCCESS = 'success';
  const ERROR = 'error';
  const INFO = 'info';
  
  protected $active;
  protected $count;
  protected $step = 0;
  protected $break = " <br />";
  public    $sub_level = 0;
  protected $sub_space = "_";
  protected $sub_space_model = "<span>%s </span>";
  
  protected $color_model = "<span style=\"color: %s;\">%s</span>";
  protected $colors = array(
    'std' => 'default',
    'success' => 'green',
    'error' => 'red',
    'info' => 'blue',
  );
  
  protected $subs;
  
  public function __construct($active = True)
  {
    $this->active = $active;
  }
  
  protected function flush()
  {
    flush();
    ob_flush();
  }
  
  public function initialyze($count, $message = Null, $color = self::STANDART, $break_line = True)
  {
    if (!$this->active)
      return;
    
    $this->count = $count;
    
    if ($message)
      $this->message ($message, $color, $break_line);
    $this->flush();
  }
  
  public function next($message = Null, $color = self::STANDART, $break_line = True)
  {
    if (!$this->active)
      return;
    
    $this->step++;
    if ($message)
      $this->message($message, $color, $break_line);
    $this->flush();
  }
  
  public function message($message, $color = self::STANDART, $break_line = True)
  {
    if (!$this->active)
      return;
    
    if ($message)
    {
      $spaces = '';
      if ($this->sub_level)
        $spaces = $this->getSubLevelSpaces();
      $step = "($this->step/$this->count) ";
      if ($this->step === 0)
        $step = "($this->count) ";
      if ($this->step === $this->count)
        $step = '';
      $break = '';
      if ($break_line)
        $break = $this->break;
      else
      {
        $step = '';
        $spaces = '';
      }
      echo $break.$spaces.$step.$this->colorMessage($message, $color);
    }
    $this->flush();
  }
  
  protected function getSubLevelSpaces()
  {
    $spaces = '';
    for ($i = 1; $i <= $this->sub_level; $i++)
    {
      $spaces .= $this->sub_space;
    }
    
    return sprintf($this->sub_space_model, $spaces);
  }
  
  protected function colorMessage($message, $color)
  {
    return sprintf($this->color_model, $this->colors[$color], $message);
  }
  
  public function setSub($sub_id)
  {
    $progress_class = get_class($this);
    $this->subs[$sub_id] = new $progress_class($this->active);
    $this->subs[$sub_id]->sub_level = $this->sub_level+1;
    return $this->getSub($sub_id);
  }
  
  public function getSub($sub_id)
  {
    if (array_key_exists($sub_id, $this->subs))
      return $this->subs[$sub_id];
    
    throw new Exception("Unknow sub $sub_id");
  }
  
  public function breakLine()
  {
    if (!$this->active)
      return;
    
    echo $this->break;
    $this->flush();
  }
  
}