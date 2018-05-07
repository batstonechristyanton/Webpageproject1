<?php 
$page = explode("/", $_SERVER['REQUEST_URI']);
$thisPage = $page[sizeof($page) -1];
?>

<header>
	<img src="img/santa.png" width="180" height="200" alt="Santa waving">
	<img src="img/raindeer.png" width="180" height="200" alt="Reindeer waving">

	<h1>My Grown-up Christmas List</h1>
	<h2>Checkin' it Twice</h2>
</header>

<nav>
	<ul>
		
		<?php if(!isset($_SESSION['username'])):?> 

			<li class="<?php if ($thisPage=="login.php") echo 'active';?>"><a href="login.php">Login</a></li>
			<li class="<?php if ($thisPage=="account.php") echo 'active';?>"><a href="account.php">Create Account</a></li>
		<?php else:?> 

			<li class="<?php if ($thisPage=="index.php") echo 'active';?>"><a href="index.php">Home</a></li>
			<li class="<?php if ($thisPage=="publiclist.php") echo 'active';?>"><a href="publiclist.php?user=<?php echo $_SESSION['id']; ?>">Public List View</a></li>
			<li class="<?php if ($thisPage=="editaccount.php") echo 'active';?>"><a href="editaccount.php">Edit Account</a></li>
			<li class="<?php if ($thisPage=="addwish.php") echo 'active';?>"><a href="addwish.php">Add Item</a></li>
			<li><a href="logout.php">Logout</a></li>
		<?php endif?>

	</ul>
</nav>  