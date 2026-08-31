<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="customer-orders-wrapper" style="background: #f8fafc; min-height: 75vh; padding: 3rem 1rem; font-family: 'Poppins', sans-serif;">
    <div style="max-width: 900px; margin: 0 auto; background: #ffffff; padding: 2.5rem; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem; flex-wrap: wrap; gap: 10px;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0;">
                <i class="fas fa-shopping-bag" style="color: #e74c3c; margin-right: 10px;"></i>
                <?= $title ?? t('Mes Commandes', 'My Orders'); ?>
            </h2>
            <a href="<?= base_url('menu'); ?>" style="padding: 10px 22px; background: #e74c3c; color: #ffffff; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 0.9rem; box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);">
                <i class="fas fa-plus"></i> <?php echo t('Nouvelle commande', 'New Order'); ?>
            </a>
        </div>

        <?php if (!empty($orders) && is_array($orders)): ?>
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <?php foreach ($orders as $order): ?>
                    <?php
                        $statusClass = strtolower($order->status);
                        $statusName = $order->status;
                        $statusBg = '#f1f5f9'; $statusColor = '#475569';

                        if ($statusClass === 'pending') {
                            $statusName = t('En attente', 'Pending');
                            $statusBg = '#fffbeb'; $statusColor = '#b45309';
                        } elseif ($statusClass === 'confirmed') {
                            $statusName = t('Confirmée', 'Confirmed');
                            $statusBg = '#ecfdf5'; $statusColor = '#047857';
                        } elseif ($statusClass === 'preparing') {
                            $statusName = t('En préparation', 'Preparing');
                            $statusBg = '#fff7ed'; $statusColor = '#c2410c';
                        } elseif ($statusClass === 'delivered') {
                            $statusName = t('Livrée', 'Delivered');
                            $statusBg = '#eff6ff'; $statusColor = '#1d4ed8';
                        } elseif ($statusClass === 'cancelled') {
                            $statusName = t('Annulée', 'Cancelled');
                            $statusBg = '#fef2f2'; $statusColor = '#b91c1c';
                        }
                    ?>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem 1.5rem; transition: all 0.2s ease;">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 10px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 10px;">
                            <div>
                                <span style="font-size: 1.1rem; font-weight: 700; color: #0f172a;">
                                    <?php echo t('Commande #', 'Order #'); ?><?= $order->id; ?>
                                </span>
                                <span style="font-size: 0.8rem; color: #64748b; margin-left: 10px;">
                                    <i class="far fa-calendar-alt"></i> <?= date('d M Y, H:i', strtotime($order->created_at)); ?>
                                </span>
                            </div>
                            <span style="background: <?= $statusBg; ?>; color: <?= $statusColor; ?>; padding: 4px 12px; border-radius: 20px; font-size: 0.82rem; font-weight: 700; text-transform: uppercase;">
                                <?= htmlspecialchars($statusName); ?>
                            </span>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; font-size: 0.88rem; color: #334155; margin-bottom: 12px;">
                            <p style="margin: 0;"><strong><i class="fas fa-box" style="color: #64748b;"></i> <?php echo t('Type :', 'Type:'); ?></strong> <?= $order->order_type === 'collect' ? t('À emporter', 'Collect') : t('Livraison', 'Delivery'); ?></p>
                            <p style="margin: 0;"><strong><i class="fas fa-credit-card" style="color: #64748b;"></i> <?php echo t('Paiement :', 'Payment:'); ?></strong> <?= $order->payment_method === 'cash' ? t('Espèces', 'Cash') : t('Carte', 'Card'); ?></p>
                            <p style="margin: 0;"><strong><i class="fas fa-user" style="color: #64748b;"></i> <?php echo t('Client :', 'Customer:'); ?></strong> <?= htmlspecialchars($order->customer_name ?? ''); ?></p>
                        </div>

                        <?php if (!empty($order->customer_address)): ?>
                            <p style="margin: 0 0 12px 0; font-size: 0.85rem; color: #64748b;">
                                <i class="fas fa-map-marker-alt" style="color: #e74c3c;"></i> <?= htmlspecialchars($order->customer_address); ?>
                            </p>
                        <?php endif; ?>

                        <div style="display: flex; align-items: center; justify-content: space-between; background: #ffffff; padding: 10px 14px; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 0.9rem; font-weight: 600; color: #475569;"><?php echo t('Montant Total :', 'Total Amount:'); ?></span>
                            <span style="font-size: 1.2rem; font-weight: 700; color: #e74c3c;">€<?= number_format($order->total_amount, 2); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 3rem 1rem;">
                <div style="width: 70px; height: 70px; background: #f1f5f9; color: #94a3b8; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1rem auto;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h3 style="font-size: 1.15rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem;"><?php echo t('Vous n\'avez encore aucune commande.', 'You have no orders yet.'); ?></h3>
                <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;"><?php echo t('Commandez vos pizzas préférées dès maintenant !', 'Order your favorite pizzas right now!'); ?></p>
                <a href="<?= base_url('menu'); ?>" style="display: inline-block; padding: 12px 26px; background: #e74c3c; color: #ffffff; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 0.9rem; box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);">
                    <?php echo t('Continuer mes achats', 'Continue Shopping'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
