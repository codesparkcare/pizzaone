<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza One - Pizzas artisanales</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Lobster&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Swiper Slider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet"
        href="<?php echo base_url('assets/css/style.css?v=' . filemtime('assets/css/style.css')); ?>">
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
                .nav-mobile-logo {
                    display: none;
                }

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
                <li><a href="<?php echo base_url(); ?>"><?php echo t('Accueil', 'Home'); ?></a></li>
                <li><a href="<?php echo base_url('menu'); ?>"><?php echo t('Menu', 'Menu'); ?></a></li>
                <li><a href="<?php echo base_url('about'); ?>"><?php echo t('À propos', 'About Us'); ?></a></li>
                <li><a href="<?php echo base_url('contact'); ?>"><?php echo t('Contact', 'Contact'); ?></a></li>
            </ul>
        </nav>

        <div class="header-actions">
            <?php
            $selected_shop_id = $this->session->userdata('selected_shop_id');
            $selected_shop_name = $this->session->userdata('selected_shop_name');
            if (!$selected_shop_name) {
                $selected_shop_name = t('Sélectionner le magasin', 'Select Shop Location');
            }
            $shop_color_class = ($selected_shop_id == '2') ? 'shop-2-active' : 'shop-1-active';
            ?>
            <div class="location-switcher">
                <button class="location-icon-btn <?php echo $shop_color_class; ?>" onclick="openLocationModal()"
                    title="<?php echo t('Magasin actuel : ', 'Current Shop: ') . htmlspecialchars($selected_shop_name); ?>">
                    <i class="fas fa-map-marker-alt"></i>
                </button>
            </div>

            <div class="language-switcher">
                <button class="lang-btn" id="user-active"
                    onclick="document.getElementById('userDropdown').classList.toggle('show'); event.stopPropagation();">
                    <i class="fas fa-user"></i>
                </button>
                <div class="lang-dropdown" id="userDropdown">
                    <?php if ($this->session->userdata('user_id')): ?>
                        <a href="<?php echo base_url('user/account'); ?>"><?php echo t('Mon Compte', 'My Account'); ?></a>
                        <a href="<?php echo base_url('user/logout'); ?>"><?php echo t('Déconnexion', 'Logout'); ?></a>
                    <?php else: ?>
                        <a href="<?php echo base_url('user/login'); ?>"><?php echo t('Connexion', 'Login'); ?></a>
                        <a href="<?php echo base_url('user/register'); ?>"><?php echo t('S\'inscrire', 'Register'); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="language-switcher">
                <button class="lang-btn" id="lang-active" onclick="toggleLangDropdown(event)">
                    <i class="fas fa-globe"></i>
                    <span class="active-lang"><?php echo strtoupper(current_lang()); ?></span>
                </button>
                <div class="lang-dropdown" id="langDropdown">
                    <a href="javascript:void(0);" onclick="changeLanguage('fr')">Français</a>
                    <a href="javascript:void(0);" onclick="changeLanguage('en')">English</a>
                </div>
            </div>

            <div class="wishlist-container">
                <a href="<?php echo base_url('wishlist'); ?>" class="cart-btn">
                    <i class="fas fa-heart"></i>
                    <?php
                    $wishlist_count = 0;
                    if ($this->session->userdata('user_id')) {
                        $wishlist_count = $this->db->where('user_id', $this->session->userdata('user_id'))->count_all_results('wishlists');
                    }
                    ?>
                    <span class="cart-badge" id="wishlistBadge"
                        style="background: #ff4757; display: <?php echo $wishlist_count > 0 ? 'flex' : 'none'; ?>;"><?php echo $wishlist_count; ?></span>
                </a>
            </div>

            <div class="cart-container">
                <a href="<?php echo base_url('cart'); ?>" class="cart-btn">
                    <i class="fas fa-shopping-basket"></i>
                    <span class="cart-badge"
                        id="cartBadge"><?php echo count($this->session->userdata('cart') ?: []); ?></span>
                </a>
            </div>

            <form action="<?php echo base_url('search'); ?>" method="GET" class="search-container">
                <input type="text" name="q" placeholder="<?php echo t('Rechercher...', 'Search...'); ?>"
                    aria-label="<?php echo t('Rechercher', 'Search'); ?>">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Location Preference Modal -->
    <div id="locationModal" class="location-modal-overlay" style="display: none;">
        <div class="location-modal-card">
            <?php if ($selected_shop_id): ?>
                <button type="button" class="location-modal-close" onclick="closeLocationModal()">&times;</button>
            <?php endif; ?>
            <div class="location-modal-header">
                <div class="location-modal-icon">
                    <i class="fas fa-store"></i>
                </div>
                <h3><?php echo t('Quelle localisation préférez-vous ?', 'Which location do you prefer?'); ?></h3>
                <p><?php echo t('Sélectionnez votre magasin préféré pour voir les produits disponibles dans votre zone.', 'Select your preferred shop to view available products in your area.'); ?>
                </p>
            </div>
            <div class="location-options">
                <!-- Shop 1: Red Theme -->
                <button type="button"
                    class="location-option-card shop-card-vlb <?php echo ($selected_shop_id == '1') ? 'active' : ''; ?>"
                    onclick="selectLocation('1', 'Villiers-le-bel')">
                    <div>
                        <div class="loc-badge loc-badge-vlb"><i class="fas fa-map-pin"></i>
                            <?php echo t('Magasin 1', 'Shop 1'); ?></div>
                        <h4>Villiers-le-bel</h4>
                        <p><i class="fas fa-location-dot"></i> 11 Place de la Tolinette, 95400 Villiers Le Bel</p>
                    </div>
                    <span class="loc-select-btn btn-vlb"><?php echo t('Sélectionner ce magasin', 'Select this shop'); ?>
                        <i class="fas fa-arrow-right"></i></span>
                </button>

                <!-- Shop 2: Blue Theme -->
                <button type="button"
                    class="location-option-card shop-card-lpb <?php echo ($selected_shop_id == '2') ? 'active' : ''; ?>"
                    onclick="selectLocation('2', 'Le Plessis-Bouchard')">
                    <div>
                        <div class="loc-badge loc-badge-lpb"><i class="fas fa-map-pin"></i>
                            <?php echo t('Magasin 2', 'Shop 2'); ?></div>
                        <h4>Le Plessis-Bouchard</h4>
                        <p><i class="fas fa-location-dot"></i> Commercial des Hauts de Saint-Nicolas, 95130 Le
                            Plessis-Bouchard</p>
                    </div>
                    <span class="loc-select-btn btn-lpb"><?php echo t('Sélectionner ce magasin', 'Select this shop'); ?>
                        <i class="fas fa-arrow-right"></i></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden Google Translate Element -->
    <div id="google_translate_element" style="visibility:hidden; height:0; width:0; overflow:hidden;"></div>

    <script>
        function openLocationModal() {
            document.getElementById('locationModal').style.display = 'flex';
        }
        function closeLocationModal() {
            document.getElementById('locationModal').style.display = 'none';
        }
        function selectLocation(shopId, shopName) {
            fetch('<?php echo base_url("welcome/set_location"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'shop_id=' + encodeURIComponent(shopId)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.reload();
                    }
                })
                .catch(err => {
                    console.error(err);
                    window.location.href = '<?php echo base_url("welcome/set_location"); ?>';
                });
        }

        function toggleLangDropdown(e) {
            if (e) e.stopPropagation();
            document.getElementById('langDropdown').classList.toggle('show');
        }

        document.addEventListener('click', function (e) {
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

        document.addEventListener('DOMContentLoaded', function () {
            <?php if (!$selected_shop_id): ?>
                openLocationModal();
            <?php endif; ?>

            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const navMenu = document.querySelector('.nav-menu');

            if (mobileMenuBtn && navMenu) {
                mobileMenuBtn.addEventListener('click', function () {
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