<?php 

// Setup autoloading.
include('autoload.php');
include('models/testmodels.php');

$object = new User();
$message = new Message();

// Create a backend object.
$backend = new bytecove\backends\MySQLBackend('localhost', 'root', '', 'lightorm');
// Enable query dumping.
$backend->debug_queries = true;

echo "<h4>Table SQL</h4>";
var_dump($object->getTableSQL());
echo "<br/>";
var_dump($message->getTableSQL());

echo "<h4>Insertion SQL</h4>";
$object->name = "Bob";
$object->email = "bob@example.com";

$backend->save($object);

$user2 = new User();
$user2->name = "Jim";
$user2->email = "nobody@razor-studios.co.uk";

$backend->save($user2);

$mail = new Message();
$mail->message = "Hello World";
$mail->title   = "Hi";
$mail->reciver = $user2;
$mail->sender  = $object;

$backend->save($mail); 

echo "<h4>Query SQL</h4>";
//var_dump($object->getQuerySQL());

echo "<h4>QuerySet SQL</h4>";
$query = User::all()
	->filter('name', '!=', 'Will Robinson');

$object->name = "Jim";
//$backend->save($object);