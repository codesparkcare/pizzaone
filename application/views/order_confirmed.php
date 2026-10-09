<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="order-confirmed-container" style="max-width: 750px; margin: 3rem auto; padding: 2.5rem; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); font-family: 'Poppins', sans-serif;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <div style="width: 70px; height: 70px; background: #e6f4ea; color: #1e8e3e; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 1rem auto;">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 style="color: #1e293b; font-size: 1.8rem; font-weight: 700; margin-bottom: 0.5rem;"><?php echo t('Merci ! Votre commande a bien été enregistrée.', 'Thank you! Your order has been placed.'); ?></h1>
        <p style="color: #64748b; font-size: 1rem;"><?php echo t('Commande ', 'Order '); ?><strong style="color: #e74c3c;">#<?= $order['id'] ?? 'N/A'; ?></strong></p>
    </div>

    <!-- WhatsApp Click to Send Order Card -->
    <?php if (isset($whatsapp_url) && $whatsapp_url): ?>
        <div style="background: linear-gradient(135deg, #25D366, #128C7E); color: #ffffff; padding: 1.8rem; border-radius: 16px; margin-bottom: 2.5rem; text-align: center; box-shadow: 0 10px 30px rgba(37, 211, 102, 0.35);">
            <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto;">
                <i class="fab fa-whatsapp" style="font-size: 2.2rem; color: #ffffff;"></i>
            </div>
            <h3 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 0.5rem; color: #ffffff;"><?php echo t('Envoyer la commande sur WhatsApp', 'Send Order to WhatsApp'); ?></h3>
            <p style="font-size: 0.95rem; opacity: 0.95; margin-bottom: 1.5rem; max-width: 550px; margin-left: auto; margin-right: auto; line-height: 1.5;">
                <?php echo t('Cliquez sur le bouton ci-dessous pour envoyer le récapitulatif complet de cette commande par WhatsApp.', 'Click the button below to send the full order summary directly via WhatsApp.'); ?>
                <br><span style="font-size: 0.85rem; background: rgba(0,0,0,0.15); padding: 2px 10px; border-radius: 12px; display: inline-block; margin-top: 6px;">Target: <strong><?= htmlspecialchars($whatsapp_phone ?? '9790587155'); ?></strong></span>
            </p>
            <a href="<?= $whatsapp_url; ?>" target="_blank" class="btn-whatsapp-send" style="display: inline-flex; align-items: center; justify-content: center; gap: 12px; padding: 15px 32px; background: #ffffff; color: #128C7E; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 1.1rem; box-shadow: 0 6px 20px rgba(0,0,0,0.2); transition: all 0.25s ease;">
                <i class="fab fa-whatsapp" style="font-size: 1.6rem; color: #25D366;"></i>
                <?php echo t('Envoyer sur WhatsApp maintenant', 'Send on WhatsApp Now'); ?>
            </a>
        </div>
    <?php endif; ?>

    <!-- Order Details Section -->
    <div style="background: #f8fafc; border-radius: 14px; padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0;">
        <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem; border-bottom: 1px dashed #cbd5e1; padding-bottom: 0.6rem;"><?php echo t('Détails de la commande', 'Order Details'); ?></h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; font-size: 0.95rem; color: #334155;">
            <p><strong><?php echo t('Type de commande :', 'Order Type:'); ?></strong> <?= $order['order_type'] === 'collect' ? t('À emporter', 'Collect') : t('Livraison', 'Delivery'); ?></p>
            <?php if (isset($order['shop']) && $order['shop']): ?>
                <p><strong><?php echo t('Magasin :', 'Shop:'); ?></strong> <?= htmlspecialchars($order['shop']->name ?? 'N/A'); ?></p>
            <?php endif; ?>
            <p><strong><?php echo t('Nom :', 'Name:'); ?></strong> <?= htmlspecialchars($order['customer_name']); ?></p>
            <p><strong><?php echo t('Téléphone :', 'Phone:'); ?></strong> <?= htmlspecialchars($order['customer_phone']); ?></p>
            <?php if (!empty($order['customer_address'])): ?>
                <p style="grid-column: 1 / -1;"><strong><?php echo t('Adresse :', 'Address:'); ?></strong> <?= htmlspecialchars($order['customer_address']); ?></p>
            <?php endif; ?>
            <p><strong><?php echo t('Moyen de paiement :', 'Payment Method:'); ?></strong> <?= $order['payment_method'] === 'cash' ? t('Espèces', 'Cash') : t('Carte bancaire', 'Credit / Debit Card'); ?></p>
            <?php if (!empty($order['notes'])): ?>
                <p style="grid-column: 1 / -1;"><strong><?php echo t('Remarques :', 'Notes:'); ?></strong> <?= htmlspecialchars($order['notes']); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Items Section -->
    <div style="background: #f8fafc; border-radius: 14px; padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0;">
        <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem; border-bottom: 1px dashed #cbd5e1; padding-bottom: 0.6rem;"><?php echo t('Articles commandés', 'Ordered Items'); ?></h2>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <?php foreach ($order['cart_items'] as $item): ?>
                <?php 
                    $size_name = $item['size_name'] ?? '';
                    if (empty($size_name) && !empty($item['size_price']) && !empty($item['product_id'])) {
                        $sz = $this->db->select('sizes.name')->from('product_sizes')->join('sizes', 'sizes.id = product_sizes.size_id')->where('product_sizes.product_id', $item['product_id'])->where('product_sizes.price', $item['size_price'])->get()->row();
                        $size_name = $sz->name ?? '';
                    }
                    $item_addons = !empty($item['addons']) ? $item['addons'] : [];
                    if (empty($item_addons)) {
                        $a_ids = array_unique(array_filter(array_merge(
                            !empty($item['addon_group_ids']) ? (array)$item['addon_group_ids'] : [],
                            !empty($item['addon_ids']) ? (array)$item['addon_ids'] : []
                        )));
                        if (!empty($a_ids)) {
                            $item_addons = $this->db->where_in('id', $a_ids)->get('addons')->result_array();
                        }
                    }
                ?>
                <li style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-size: 0.95rem; color: #334155;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <span>
                            <strong style="color: #e74c3c;"><?= intval($item['quantity']); ?>x</strong> 
                            <?= htmlspecialchars($item['product_name']); ?>
                            <?php if (!empty($size_name)): ?>
                                <span style="font-size: 0.75rem; background: #eff6ff; color: #1d4ed8; padding: 2px 7px; border-radius: 4px; margin-left: 6px; font-weight: 600; border: 1px solid #dbeafe;"><?= htmlspecialchars($size_name); ?></span>
                            <?php endif; ?>
                        </span>
                        <span style="font-weight: 600; color: #1e293b;">€<?= number_format($item['item_total'], 2); ?></span>
                    </div>
                    <?php if (!empty($item_addons)): ?>
                        <div style="margin-top: 5px; display: flex; flex-wrap: wrap; gap: 4px;">
                            <?php foreach ($item_addons as $ad): 
                                $ad_name = is_array($ad) ? ($ad['name'] ?? '') : ($ad->name ?? (string)$ad);
                                $ad_price = is_array($ad) ? floatval($ad['price'] ?? 0) : floatval($ad->price ?? 0);
                                $is_sans = stripos($ad_name, 'sans ') === 0;
                            ?>
                                <span style="font-size: 0.72rem; padding: 2px 6px; border-radius: 4px; background: <?= $is_sans ? '#fef2f2' : '#f0fdf4'; ?>; color: <?= $is_sans ? '#b91c1c' : '#15803d'; ?>; border: 1px solid <?= $is_sans ? '#fecaca' : '#bbf7d0'; ?>; font-weight: 600;">
                                    <i class="fas <?= $is_sans ? 'fa-ban' : 'fa-check'; ?>" style="font-size: 0.65rem;"></i> <?= htmlspecialchars($ad_name); ?><?= ($ad_price > 0) ? ' (+€' . number_format($ad_price, 2) . ')' : ''; ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <div style="margin-top: 1.2rem; text-align: right; font-size: 1rem; color: #1e293b;">
            <p style="margin-bottom: 4px;"><?php echo t('Sous-total :', 'Subtotal:'); ?> <strong>€<?= number_format($order['subtotal'], 2); ?></strong></p>
            <?php if ($order['delivery_fee'] > 0): ?>
                <p style="margin-bottom: 4px;"><?php echo t('Frais de livraison :', 'Delivery Fee:'); ?> <strong>€<?= number_format($order['delivery_fee'], 2); ?></strong></p>
            <?php endif; ?>
            <p style="font-size: 1.35rem; color: #e74c3c; font-weight: 700; margin-top: 8px;"><?php echo t('Total :', 'Total:'); ?> €<?= number_format($order['total'], 2); ?></p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <?php if ($this->session->userdata('user_id')): ?>
            <a href="<?php echo base_url('user/account'); ?>" class="btn" style="display: inline-block; padding: 12px 28px; background: #e74c3c; color: #ffffff; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 0.95rem; box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3); transition: all 0.2s ease;">
                <i class="fas fa-list-ul"></i> <?php echo t('Voir mes commandes', 'View My Orders'); ?>
            </a>
        <?php else: ?>
            <a href="<?php echo base_url('menu'); ?>" class="btn" style="display: inline-block; padding: 12px 28px; background: #e74c3c; color: #ffffff; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 0.95rem; box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3); transition: all 0.2s ease;">
                <i class="fas fa-utensils"></i> <?php echo t('Continuer mes achats', 'Continue Shopping'); ?>
            </a>
        <?php endif; ?>
    </div>
</div>
