<?php

// Include models and autoload.
include('autoload.php');
include('models/Message.php');

// Create a backend connection. (please don't use this in production.)
$db = mysqli_connect('localhost', 'root', '', 'lightorm');
$backend = new \Pallet\backends\MySQLBackend($db);
$backend->debug_queries = true;

// Recieve messages.
if(isset($_POST['body']) && isset($_POST['title']))
{
	$message = new Message();
	$message->message = $_POST['body'];
	$message->title = $_POST['title'];
	$message->ip = $_SERVER['REMOTE_ADDR'];
	
	$user = new User();
	$user->email = 'test@example.com';
	$backend->save($user);
	
	$message->user = $user;
	
	$backend->save($message);
}

if(isset($_GET['schema'])) {
    $m = new Message();
    print($m->getTableSQL());
}

$messages = Message::all()->sort('id');

// No way to lazy-execute yet.
$messages->execute($backend);
?>
<h1>Guestbook</h1>

<h2>Leave a message</h2>
<form method="POST">
	<label for="title">Title</label>
	<input type="text" name="title"></input><br/>
	<label for="body">Message</label>
	<input type="text" name="body"></input>
	<input type="submit" value="Leave Message"></input>
</form>

<h2>Past Messages</h2>
<?php

foreach($messages as $message) {
	echo "<h3>$message->title</h3>";
	echo "<p>$message->message</p>";
	$email = $message->user->email;
	echo "<p>$email</p>";
}
