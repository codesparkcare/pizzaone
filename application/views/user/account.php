<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="user-account-wrapper" style="background: #f8fafc; min-height: 80vh; padding: 2.5rem 1rem; font-family: 'Poppins', sans-serif;">
    <div style="max-width: 1140px; margin: 0 auto;">
        
        <!-- Top Profile Welcome Banner -->
        <div style="background: linear-gradient(135deg, #1e293b, #0f172a); color: #ffffff; padding: 2rem 2.5rem; border-radius: 18px; margin-bottom: 2rem; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 65px; height: 65px; background: linear-gradient(135deg, #e74c3c, #c0392b); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 700; box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);">
                    <?= strtoupper(substr($user->first_name ?? 'U', 0, 1)); ?>
                </div>
                <div>
                    <h1 style="font-size: 1.6rem; font-weight: 700; margin: 0 0 4px 0; color: #ffffff;"><?php echo t('Bonjour, ', 'Hello, '); ?><?= htmlspecialchars($user->first_name . ' ' . $user->last_name); ?> !</h1>
                    <p style="margin: 0; opacity: 0.8; font-size: 0.9rem;"><i class="fas fa-envelope"></i> <?= htmlspecialchars($user->email); ?></p>
                </div>
            </div>
            <a href="<?php echo base_url('user/logout'); ?>" style="padding: 10px 20px; background: rgba(255,255,255,0.1); color: #ffffff; border: 1px solid rgba(255,255,255,0.2); border-radius: 50px; text-decoration: none; font-size: 0.88rem; font-weight: 600; transition: all 0.25s ease;">
                <i class="fas fa-sign-out-alt"></i> <?php echo t('Déconnexion', 'Logout'); ?>
            </a>
        </div>

        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 2rem;">
            
            <!-- Left Navigation Sidebar -->
            <div class="account-sidebar">
                <div style="background: #ffffff; border-radius: 16px; padding: 1.2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 6px;">
                        <li>
                            <a href="#orders-section" onclick="switchAccountTab('orders')" id="tab-btn-orders" class="account-nav-btn active" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 18px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 0.92rem; color: #e74c3c; background: #fff5f5; transition: all 0.2s ease;">
                                <span><i class="fas fa-shopping-bag" style="width: 22px;"></i> <?php echo t('Mes Commandes', 'My Orders'); ?></span>
                                <span style="background: #e74c3c; color: #fff; font-size: 0.75rem; padding: 2px 8px; border-radius: 12px; font-weight: 700;"><?= count($orders ?? []); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#profile-section" onclick="switchAccountTab('profile')" id="tab-btn-profile" class="account-nav-btn" style="display: flex; align-items: center; padding: 12px 18px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 0.92rem; color: #64748b; background: transparent; transition: all 0.2s ease;">
                                <i class="fas fa-user-circle" style="width: 22px;"></i> <?php echo t('Mon Profil', 'My Profile'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('wishlist'); ?>" style="display: flex; align-items: center; padding: 12px 18px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 0.92rem; color: #64748b; background: transparent; transition: all 0.2s ease;">
                                <i class="fas fa-heart" style="width: 22px; color: #ff4757;"></i> <?php echo t('Ma Liste d\'envies', 'My Wishlist'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="account-content-area">

                <!-- Orders Section -->
                <div id="section-orders" class="account-section-block">
                    <div style="background: #ffffff; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                            <h2 style="font-size: 1.35rem; font-weight: 700; color: #0f172a; margin: 0;">
                                <i class="fas fa-receipt" style="color: #e74c3c; margin-right: 8px;"></i>
                                <?php echo t('Historique de mes commandes', 'My Order History'); ?>
                            </h2>
                            <a href="<?php echo base_url('menu'); ?>" style="padding: 8px 16px; background: #e74c3c; color: #ffffff; border-radius: 50px; text-decoration: none; font-size: 0.85rem; font-weight: 600;">
                                <i class="fas fa-plus"></i> <?php echo t('Nouvelle commande', 'New Order'); ?>
                            </a>
                        </div>

                        <?php if (!empty($orders) && is_array($orders)): ?>
                            <div style="display: flex; flex-direction: column; gap: 16px;">
                                <?php foreach ($orders as $ord): ?>
                                    <?php
                                        $statusClass = strtolower($ord->status);
                                        $statusLabel = $ord->status;
                                        $statusBg = '#f1f5f9';
                                        $statusColor = '#475569';

                                        if ($statusClass === 'pending') {
                                            $statusLabel = t('En attente', 'Pending');
                                            $statusBg = '#fffbeb'; $statusColor = '#b45309';
                                        } elseif ($statusClass === 'confirmed') {
                                            $statusLabel = t('Confirmée', 'Confirmed');
                                            $statusBg = '#ecfdf5'; $statusColor = '#047857';
                                        } elseif ($statusClass === 'preparing') {
                                            $statusLabel = t('En préparation', 'Preparing');
                                            $statusBg = '#fff7ed'; $statusColor = '#c2410c';
                                        } elseif ($statusClass === 'delivered') {
                                            $statusLabel = t('Livrée', 'Delivered');
                                            $statusBg = '#eff6ff'; $statusColor = '#1d4ed8';
                                        } elseif ($statusClass === 'cancelled') {
                                            $statusLabel = t('Annulée', 'Cancelled');
                                            $statusBg = '#fef2f2'; $statusColor = '#b91c1c';
                                        }
                                    ?>
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem 1.5rem; transition: all 0.2s ease;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 12px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 10px;">
                                            <div>
                                                <span style="font-size: 1.1rem; font-weight: 700; color: #0f172a;">
                                                    <?php echo t('Commande #', 'Order #'); ?><?= $ord->id; ?>
                                                </span>
                                                <span style="font-size: 0.8rem; color: #64748b; margin-left: 10px;">
                                                    <i class="far fa-calendar-alt"></i> <?= date('d/m/Y H:i', strtotime($ord->created_at)); ?>
                                                </span>
                                            </div>
                                            <span style="background: <?= $statusBg; ?>; color: <?= $statusColor; ?>; padding: 4px 12px; border-radius: 20px; font-size: 0.82rem; font-weight: 700; text-transform: uppercase;">
                                                <?= htmlspecialchars($statusLabel); ?>
                                            </span>
                                        </div>

                                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; font-size: 0.88rem; color: #334155; margin-bottom: 12px;">
                                            <p style="margin: 0;"><strong><i class="fas fa-store" style="color: #64748b;"></i> <?php echo t('Magasin :', 'Shop:'); ?></strong> <?= htmlspecialchars($ord->shop_name ?? 'Villiers-le-bel'); ?></p>
                                            <p style="margin: 0;"><strong><i class="fas fa-box" style="color: #64748b;"></i> <?php echo t('Type :', 'Type:'); ?></strong> <?= $ord->order_type === 'collect' ? t('À emporter', 'Collect') : t('Livraison', 'Delivery'); ?></p>
                                            <p style="margin: 0;"><strong><i class="fas fa-credit-card" style="color: #64748b;"></i> <?php echo t('Paiement :', 'Payment:'); ?></strong> <?= $ord->payment_method === 'cash' ? t('Espèces', 'Cash') : t('Carte', 'Card'); ?></p>
                                        </div>

                                        <?php if (!empty($ord->customer_address)): ?>
                                            <p style="margin: 0 0 12px 0; font-size: 0.85rem; color: #64748b;">
                                                <i class="fas fa-map-marker-alt" style="color: #e74c3c;"></i> <?= htmlspecialchars($ord->customer_address); ?>
                                            </p>
                                        <?php endif; ?>

                                        <div style="display: flex; align-items: center; justify-content: space-between; background: #ffffff; padding: 10px 14px; border-radius: 10px; border: 1px solid #e2e8f0;">
                                            <span style="font-size: 0.9rem; font-weight: 600; color: #475569;"><?php echo t('Montant Total :', 'Total Amount:'); ?></span>
                                            <span style="font-size: 1.2rem; font-weight: 700; color: #e74c3c;">€<?= number_format($ord->total_amount, 2); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="text-align: center; padding: 3rem 1rem;">
                                <div style="width: 70px; height: 70px; background: #f1f5f9; color: #94a3b8; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1rem auto;">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <h3 style="font-size: 1.15rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem;"><?php echo t('Aucune commande enregistrée', 'No orders found'); ?></h3>
                                <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;"><?php echo t('Vous n\'avez pas encore effectué de commande.', 'You have not placed any orders yet.'); ?></p>
                                <a href="<?php echo base_url('menu'); ?>" style="display: inline-block; padding: 12px 26px; background: #e74c3c; color: #ffffff; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 0.9rem; box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);">
                                    <?php echo t('Découvrir notre menu', 'Browse Our Menu'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Profile Section -->
                <div id="section-profile" class="account-section-block" style="display: none;">
                    <div style="background: #ffffff; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 2rem;">
                        <h2 style="font-size: 1.35rem; font-weight: 700; color: #0f172a; margin: 0 0 1.5rem 0; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                            <i class="fas fa-user-edit" style="color: #e74c3c; margin-right: 8px;"></i>
                            <?php echo t('Informations personnelles', 'Personal Information'); ?>
                        </h2>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 2rem;">
                            <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <span style="font-size: 0.78rem; font-weight: 600; text-transform: uppercase; color: #94a3b8; display: block; margin-bottom: 4px;"><?php echo t('Nom complet', 'Full Name'); ?></span>
                                <span style="font-size: 1rem; font-weight: 700; color: #1e293b;"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></span>
                            </div>
                            <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <span style="font-size: 0.78rem; font-weight: 600; text-transform: uppercase; color: #94a3b8; display: block; margin-bottom: 4px;"><?php echo t('Adresse e-mail', 'Email Address'); ?></span>
                                <span style="font-size: 1rem; font-weight: 700; color: #1e293b;"><?= htmlspecialchars($user->email); ?></span>
                            </div>
                            <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <span style="font-size: 0.78rem; font-weight: 600; text-transform: uppercase; color: #94a3b8; display: block; margin-bottom: 4px;"><?php echo t('Numéro de téléphone', 'Phone Number'); ?></span>
                                <span style="font-size: 1rem; font-weight: 700; color: #1e293b;"><?= htmlspecialchars($user->phone ? $user->phone : t('Non renseigné', 'Not provided')); ?></span>
                            </div>
                        </div>

                        <h3 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin: 0 0 1rem 0; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">
                            <i class="fas fa-map-marked-alt" style="color: #e74c3c; margin-right: 8px;"></i>
                            <?php echo t('Adresse de livraison', 'Delivery Address'); ?>
                        </h3>
                        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 0.95rem; color: #334155; line-height: 1.6;">
                            <?= $user->address ? nl2br(htmlspecialchars($user->address)) : t('Aucune adresse de livraison enregistrée.', 'No delivery address saved.'); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function switchAccountTab(tabName) {
        var ordersSec = document.getElementById('section-orders');
        var profileSec = document.getElementById('section-profile');
        var ordersBtn = document.getElementById('tab-btn-orders');
        var profileBtn = document.getElementById('tab-btn-profile');

        if (tabName === 'profile') {
            ordersSec.style.display = 'none';
            profileSec.style.display = 'block';
            
            profileBtn.style.color = '#e74c3c';
            profileBtn.style.background = '#fff5f5';
            
            ordersBtn.style.color = '#64748b';
            ordersBtn.style.background = 'transparent';
        } else {
            profileSec.style.display = 'none';
            ordersSec.style.display = 'block';

            ordersBtn.style.color = '#e74c3c';
            ordersBtn.style.background = '#fff5f5';
            
            profileBtn.style.color = '#64748b';
            profileBtn.style.background = 'transparent';
        }
    }

    // Handle hash in URL if navigating directly
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash === '#profile-section') {
            switchAccountTab('profile');
        }
    });
</script>
