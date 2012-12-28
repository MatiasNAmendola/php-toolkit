<?php
include('../autoload.php');
include('ExampleForm.php');

$form = new ExampleForm($_POST);

echo $form->getMarkup();
