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
  
  protected $subs = array();
  protected $errors = array();
  
  public function __construct($active = True)
  {
    $this->active = $active;
  }
  
  protected function flush()
  {
    if (ob_get_contents())
    {
      flush();
      ob_flush();
    }
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
    
    $this->incrementStep();
    if ($message)
      $this->message($message, $color, $break_line);
    $this->flush();
    $this->cleanStep();
  }
  
  protected function incrementStep()
  {
    $this->step++;
  }
  
  protected function cleanStep()
  {
    if ($this->step == $this->count)
    {
      $this->step = 0;
      $this->count = Null;
    }
  }
  
  // TODO: Nettoyer la fonction
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
      if ($this->count === Null)
        $step = '';
      $break = '';
      if ($break_line)
      {
        $break = $this->break;
      }
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
  
  public function __destruct() {
    $this->displayErrors();
    $this->breakLine();
  }
  
  public function successDot()
  {
    $this->message('.', self::SUCCESS, False);
  }
  
  public function error($error = Null)
  {
    $this->message('ERROR', self::ERROR, False);
    if ($error)
      $this->errors[] = $error;
  }
  
  public function displayErrors()
  {
    if (count($this->errors))
    {
      $this->breakLine();
      $this->setSub('__errors')->initialyze(count($this->errors), 'Errors detail', self::ERROR);
      foreach ($this->errors as $error)
      {
        if (is_a($error, 'Exception'))
        {
          $this->getSub('__errors')->next('Caught exception: '. $error->getMessage());
          $this->getSub('__errors')->displayTraceError($error, $this->getSub('__errors'));
          //$this->getSub('__errors')->message($error->getTraceAsString())
        }
        else
          $this->getSub('__errors')->next($error);
      }
    }
  }
  
  protected function displayTraceError(Exception $exception, ProgressDisplayer $displayer)
  {
    $displayer->setSub('__trace')->initialyze(count($exception->getTrace())+1, 'Trace');
    foreach (explode("\n", $exception->getTraceAsString()) as $trace)
    {
      $displayer->getSub('__trace')->next($trace);
    }
  }
  
}