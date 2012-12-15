<?php 

// Setup autoloading.
include('autoload.php');
include('models/testmodels.php');

$object = new TestModel();

// Create a backend object.
$backend = new bytecove\backends\MySQLBackend('localhost', 'root', '', 'lightorm');
// Enable query dumping.
$backend->debug_queries = true;

echo "<h4>Table SQL</h4>";
var_dump($object->getTableSQL());

echo "<h4>Insertion SQL</h4>";
$object->name = "Bob";
$object->descr = "Fired";
$object->likes = 50;

$backend->save($object);

echo "<h4>Query SQL</h4>";
//var_dump($object->getQuerySQL());

echo "<h4>QuerySet SQL</h4>";
$query = TestModel::all()
	->filter('likes', '<=', 50)
	->filter('name', '!=', 'Will Robinson');

//$query->execute($backend);

/*while (($r = $query->next()) != NULL) {
	print_r($r);
}*/

$object->name = "Jim";
$backend->save($object);

//var_dump($query->execute($backend));
//print_r($object);
