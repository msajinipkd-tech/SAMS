<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo SITENAME; ?>
    </title>
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'buyer'): ?>
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/style.css">
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="navbar">
        <div class="container">
            <a href="<?php echo URLROOT; ?>" class="brand">SAMS</a>
            <ul class="nav-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li>Welcome,
                        <?php echo $_SESSION['user_username']; ?>
                    </li>
                    <?php if ($_SESSION['user_role'] == 'admin'): ?>
                        <li><a href="<?php echo URLROOT; ?>/admin/dashboard">Dashboard</a></li>
                    <?php elseif ($_SESSION['user_role'] == 'farmer'): ?>
                        <li><a href="<?php echo URLROOT; ?>/farmer/dashboard">Dashboard</a></li>
                    <?php elseif ($_SESSION['user_role'] == 'buyer'): ?>
                        <li><a href="<?php echo URLROOT; ?>/buyer/dashboard">Dashboard</a></li>
                        <li><a href="<?php echo URLROOT; ?>/buyer/orders">My Orders</a></li>
                        <li><a href="<?php echo URLROOT; ?>/buyer/cart">Cart</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo URLROOT; ?>/users/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?php echo URLROOT; ?>/users/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="container main-content">