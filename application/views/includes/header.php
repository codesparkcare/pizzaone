<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza One - Authentic Italian Pizzas</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Lobster&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Swiper Slider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css?v=' . filemtime('assets/css/style.css')); ?>">
</head>

<body>
    <!-- Pizza Loader -->
    <div id="pizza-loader" class="pizza-loader-container">
        <div class="pizza-slice"></div>
    </div>
    <header class="main-header">
        <div class="header-left" style="display: flex; align-items: center; gap: 15px;">
            <button class="mobile-menu-btn" id="mobileMenuBtn" style="margin: 0 !important;">
                <i class="fas fa-bars"></i>
            </button>

            <div class="logo" style="margin: 0 !important;">
                <a href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Pizza One Logo">
                </a>
            </div>
        </div>

        <nav class="nav-menu">
            <style>
                .nav-mobile-logo { display: none; }
                @media (max-width: 992px) {
                    .nav-mobile-logo {
                        display: block;
                        text-align: center;
                        padding-bottom: 20px;
                        margin-bottom: 10px;
                        border-bottom: 1px solid #f0f0f0;
                        margin-top: -20px;
                    }
                    .nav-mobile-logo img {
                        height: 45px;
                    }
                }
            </style>
            <div class="nav-mobile-logo">
                <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Pizza One Logo">
            </div>
            <ul>
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="<?php echo base_url('menu'); ?>">Menu</a></li>
                <li><a href="<?php echo base_url('about'); ?>">About Us</a></li>
                <li><a href="<?php echo base_url('contact'); ?>">Contact</a></li>
            </ul>
        </nav>

        <div class="header-actions">
            
            <div class="language-switcher" style="margin-right: 15px;">
                <button class="lang-btn" id="user-active" onclick="document.getElementById('userDropdown').classList.toggle('show'); event.stopPropagation();">
                    <i class="fas fa-user"></i>
                </button>
                <div class="lang-dropdown" id="userDropdown">
                    <?php if($this->session->userdata('user_id')): ?>
                        <a href="<?php echo base_url('user/account'); ?>">My Account</a>
                        <a href="<?php echo base_url('user/logout'); ?>">Logout</a>
                    <?php else: ?>
                        <a href="<?php echo base_url('user/login'); ?>">Login</a>
                        <a href="<?php echo base_url('user/register'); ?>">Register</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="language-switcher">
                <button class="lang-btn" id="lang-active" onclick="toggleLangDropdown(event)">
                    <i class="fas fa-globe"></i>
                    <span class="active-lang">EN</span>
                </button>
                <div class="lang-dropdown" id="langDropdown">
                    <a href="javascript:void(0);" onclick="changeLanguage('en')">English</a>
                    <a href="javascript:void(0);" onclick="changeLanguage('fr')">French</a>
                </div>
            </div>

            <div class="wishlist-container" style="margin-right: 10px;">
                <a href="<?php echo base_url('wishlist'); ?>" class="cart-btn">
                    <i class="fas fa-heart"></i>
                </a>
            </div>

            <div class="cart-container">
                <a href="<?php echo base_url('cart'); ?>" class="cart-btn">
                    <i class="fas fa-shopping-basket"></i>
                    <span class="cart-badge" id="cartBadge"><?php echo count($this->session->userdata('cart') ?: []); ?></span>
                </a>
            </div>

            <form action="<?php echo base_url('search'); ?>" method="GET" class="search-container">
                <input type="text" name="q" placeholder="Search..." aria-label="Search">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Hidden Google Translate Element -->
    <div id="google_translate_element" style="visibility:hidden; height:0; width:0; overflow:hidden;"></div>

    <script>
    function toggleLangDropdown(e) {
        if (e) e.stopPropagation();
        document.getElementById('langDropdown').classList.toggle('show');
    }

    document.addEventListener('click', function(e) {
        const langBtn = document.getElementById('lang-active');
        const langDropdown = document.getElementById('langDropdown');
        if (langBtn && langDropdown && !langBtn.contains(e.target) && !langDropdown.contains(e.target)) {
            langDropdown.classList.remove('show');
        }

        const userBtn = document.getElementById('user-active');
        const userDropdown = document.getElementById('userDropdown');
        if (userBtn && userDropdown && !userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.remove('show');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navMenu = document.querySelector('.nav-menu');
        
        if (mobileMenuBtn && navMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                
                // Toggle icon
                const icon = mobileMenuBtn.querySelector('i');
                if (navMenu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
        }
    });
    </script>