<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar sticky-top vh-100 border-end p-3">
        <div class="logo" id="sidebar-logo">Fin<span>Grit</span></div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <div class="link-container">
            <a href="dashboard.php" class="sidebar-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>
        </div>
        </li>
        <li class="nav-item">
            <div class="link-container">
            <a href="transactions.php" class="sidebar-link <?php echo $current_page === 'transactions.php' ? 'active' : ''; ?>">
            <i class="bi bi-credit-card"></i>
            Transactions
        </a>
        </div>
        </li>
        <li class="nav-item">
            <div class="link-container">
            <a href="goals.php" class="sidebar-link <?php echo $current_page === 'goals.php' ? 'active' : ''; ?>">
            <i class="bi bi-bullseye"></i>
            Goals
        </a>
        </div>
        </li>
        <li class="nav-item">
            <div class="link-container">
            <a href="analytics.php" class="sidebar-link <?php echo $current_page === 'analytics.php' ? 'active' : ''; ?>">
            <i class="bi bi-pie-chart"></i>
            Analytics
        </a>
        </div>
        </li>
        <li class="nav-item">
            <div class="link-container">
            <a href="profile.php" class="sidebar-link <?php echo $current_page === 'profile.php' ? 'active' : ''; ?>">
            <i class="bi bi-person"></i>
            Profile
        </a>
        </div>
        </li>
        <li class="nav-item">
            <div class="link-container">
            <a href="logout.php" class="sidebar-link">
            <i class="bi bi-box-arrow-left"></i>
            Logout
        </a>
        </div>
        </li>
    </ul>
</aside>
