ProgressDisplayer
=================

## Introduction

Petite librairie permettant d'afficher la progression d'une tache. Utile lorsque 
l'on a besoin d'éxecuter une longue tache tout en tenant informé l'utilisateur.

## Exemple

Exemple pour un script en CLI. LE fonctionnement est le même pour un script 
dérrière un serveur Web, sauf qu'il faut utiliser la classe ProgressDisplayer.

``` php
require_once 'CommandProgressDisplayer.php';

//// Pour un usage avec un serveur web:
// require_once 'ProgressDisplayer.php';
// header('Content-type: text/html; charset=utf-8');
// $progress = new ProgressDisplayer();

$progress = new CommandProgressDisplayer();

$progress->message('Execution de la procédure "Démo"', CommandProgressDisplayer::INFO);

$progress->initialyze(3, "Etapes à effectuer: 3");
$progress->breakLine();

for ($etape_count = 1; $etape_count <= 3; $etape_count++)
{
  $progress->breakLine();
  $progress->next("Execution de l'étape $etape_count ... ");
  sleep(1);
  $progress->message("Ok", CommandProgressDisplayer::SUCCESS, False);
  
  $progress->setSub('subtask');
  for ($subtask_count = 1; $subtask_count <= 2; $subtask_count++)
  {
    $progress->getSub('subtask')->message("Execution d'une sous-tache");
  }
  
}

$progress->message('Terminé !');

/////////////////


$progress = new CommandProgressDisplayer();

$progress->message('Execution de la procédure "Démo à point"', CommandProgressDisplayer::INFO);
$progress->breakLine();
for ($etape_count = 1; $etape_count <= 100; $etape_count++)
{
  $progress->successDot();
  usleep(10000);
}

$progress->message('Terminé !');

/////////////////


$progress = new CommandProgressDisplayer();

$progress->message('Execution de la procédure "Démo à point avec Erreur"', CommandProgressDisplayer::INFO);
$progress->breakLine();
for ($etape_count = 1; $etape_count <= 100; $etape_count++)
{
  
  try {
    
    if ($etape_count == 20 || $etape_count == 70)
      throw new Exception('Hello world, we have a problem !');
    
    $progress->successDot();
  } catch (Exception $ex) {
    $progress->error($ex);
  }
  
  usleep(10000);
}

$progress->message('Terminé !');
```

Rendu:
![screenshot](https://raw.githubusercontent.com/buxx/ProgressDisplayer/master/ProgressDisplayer.png)

## Autre

 * Il est posible d'utiliser le ProgressDisplayer en mode silencieux: Si vous passez le paramètre
$silent à True lors de la construction, celui-ci n'affichera rien hormis les erreurs
en fin de procédure.

 * Pour désactiver la couleur, passez $coloration a False lors de la construction.
