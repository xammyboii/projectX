<!-- 
    Name: Xaviery Abados
    WEBD-2008 CMS Project
    Description: Header section of the website. Also included the search bar and nav menu.
-->
<header>
    <h1><a href="index.php">House of X</a></h1>
    <img alt="logo" />
</header>

<!-- TRIAL. MAY CHANGE. JUST WANT TO SEE THE LOOK FOR NOW -->
<form method="post" action="">
    <label for="user-search">X-Men</label>
    <input name="user-search" id="user-search" placeholder="Search for X">
    <input type="submit" value="Search">
</form>

<nav>
    <ul id="menu">
<?php if(isset($_SESSION['user_name']) && (isset($_SESSION['admin_access']) == 1)): ?>
        <li><a href="index.php">Home</a></li>
        <li>Welcome, <?= $_SESSION['user_name'] ?></li>
        <li><a href="logout.php">Logout</a></li>
<?php else: ?>
        <li><a href="index.php">Home</a></li>
        <li><a href="login.php">Login</a></li>
<?php endif ?>
    </ul>
</nav>