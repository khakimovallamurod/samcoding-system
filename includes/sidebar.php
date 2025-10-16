<style>
/* Sidebar */
#sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 220px;
    background: #1e1e2f;   /* quyuq fon */
    color: #fff;
    display: flex;
    flex-direction: column;
    border-right: 1px solid rgba(255,255,255,0.1);
}

/* Logo */
#sidebar .sidebar-header {
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
#sidebar .sidebar-header img {
    max-width: 60px;
    margin-bottom: 8px;
}
#sidebar .sidebar-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

/* Menu */
#sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
    flex: 1;
}
#sidebar ul li {
    display: block;
}
#sidebar ul li a {
    display: flex;
    align-items: center;
    padding: 12px 18px;
    font-size: 14px;
    font-weight: 500;
    color: #cfcfd9;
    text-decoration: none;
    transition: all 0.2s;
}
#sidebar ul li a i {
    margin-right: 10px;
    font-size: 16px;
}

/* Hover effekti */
#sidebar ul li a:hover {
    background: #2d2d44;
    color: #fff;
}

/* Aktiv sahifa */
#sidebar ul li.active > a {
    background: #0066cc;
    color: #fff;
}
</style>

<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
?>

<nav id="sidebar">
    <div class="sidebar-header">
        <h4>SamCoding</h4>
    </div>
    <ul>
        <li class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">
            <a href="index.php"><i class="fa fa-home"></i>Bosh sahifa</a>
        </li>
        <li class="<?= ($current_page == 'problems.php') ? 'active' : '' ?>">
            <a href="problems.php"><i class="fa fa-code"></i> Masalalar</a>
        </li>
        <li class="<?= ($current_page == 'olympiads.php') ? 'active' : '' ?>">
            <a href="olympiads.php"><i class="fa fa-trophy"></i>Olimpiadalar</a>
        </li>
        <li class="<?= ($current_page == 'ranking.php') ? 'active' : '' ?>">
            <a href="ranking.php"><i class="fa fa-signal"></i>Reyting</a>
        </li>
        <li class="<?= ($current_page == 'settings.php') ? 'active' : '' ?>">
            <a href="settings.php"><i class="fa fa-cog"></i>Sozlamalar</a>
        </li>
        <li>
            <a href="auth/logout.php"><i class="fa fa-sign-out"></i>Chiqish</a>
        </li>
    </ul>
</nav>
