<?php

// Include models and autoload.
include('autoload.php');
include('models/Message.php');

// Import Chloroform 
use \Chloroform\addons\PalletForm;

// Create a backend connection. (please don't use this in production.)
$backend = new \Pallet\backends\MySQLBackend('localhost', 'root', '', 'lightorm');

$message = new Message();
$form = new PalletForm($message);
$form->fields = array('title', 'message');
// Recieve messages.
if($form->isValid())
{
	$message = $form->save();
	
	$message->ip = $_SERVER['REMOTE_ADDR'];
	
	$user = new User();
	$user->email = 'test@example.com';
	$backend->save($user);
	
	$message->user = $user;
	
	$backend->save($message);
}

$messages = Message::all();

// No way to lazy-execute yet.
$messages->execute($backend);
?>
<h1>Guestbook</h1>

<h2>Leave a message</h2>
<!--<form method="POST">
	<label for="title">Title</label>
	<input type="text" name="title"></input><br/>
	<label for="body">Message</label>
	<input type="text" name="body"></input>
	<input type="submit" value="Leave Message"></input>
</form>-->
<?= $form->getMarkup() ?>

<h2>Past Messages</h2>
<?php
foreach($messages as $message) {
	echo "<h3>$message->title</h3>";
	echo "<p>$message->message</p>";
	$email = $message->user->email;
	echo "<p>$email</p>";
}
