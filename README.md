ProgressDisplayer
=================

## Introduction

Petite librairie permettant d'afficher la progression d'une tache. Utile lorsque 
l'on a besoin d'éxecuter une longue tache tout en tenant informé l'utilisateur.

## Exemple

``` php
<?php
require_once 'CommandProgressDisplayer.php';

//// Pour un usage avec un serveur web:
// require_once 'ProgressDisplayer.php';
// header('Content-type: text/html; charset=utf-8');
// $progress = new ProgressDisplayer();

$progress = new CommandProgressDisplayer();

$progress->message('Execution de la procédure "Démo"', CommandProgressDisplayer::INFO);
$progress->initialyze(6, "Etapes à effectuer: 6");

for ($etape_count = 1; $etape_count <= 6; $etape_count++)
{
  $progress->next("Execution de l'étape $etape_count ... ");
  sleep(1);
  $progress->message("Ok", CommandProgressDisplayer::SUCCESS, False);
}

$progress->message('Terminé !');
```
