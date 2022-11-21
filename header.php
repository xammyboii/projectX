/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Header section of the website. Also included the search bar and nav menu.
 */

<header>
    <h1><a href="index.php">House of X</a></h1>
    <img alt="logo" />
</header>

<form method="post" action="">
    <label for="user-search">X-Men</label>
    <input name="user-search" id="user-search">
    <input type="submit" value="Search">
</form>

<nav>
    <ul id="menu">
        <li><a href="index.php">Home</a></li>
        <li><a href="characters.php">Characters</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>