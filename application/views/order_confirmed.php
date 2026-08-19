<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="order-confirmed" style="max-width: 800px; margin: 2rem auto; padding: 2rem; background: rgba(255,255,255,0.9); border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); font-family: 'Inter', sans-serif;">
    <h1 style="color: #2d8cf0; text-align: center; margin-bottom: 1.5rem;"><?php echo t('Merci ! Votre commande a bien été enregistrée.', 'Thank you! Your order has been placed.'); ?></h1>
    <p><strong><?php echo t('Type de commande :', 'Order Type:'); ?></strong> <?= $order['order_type'] === 'collect' ? t('À emporter', 'Collect') : t('Livraison', 'Delivery'); ?></p>
    <?php if (isset($order['shop']) && $order['shop']): ?>
        <p><strong><?php echo t('Magasin :', 'Shop:'); ?></strong> <?= $order['shop']->name ?? 'N/A'; ?></p>
    <?php endif; ?>
    <p><strong><?php echo t('Nom :', 'Name:'); ?></strong> <?= $order['customer_name']; ?></p>
    <p><strong><?php echo t('Téléphone :', 'Phone:'); ?></strong> <?= $order['customer_phone']; ?></p>
    <?php if (!empty($order['customer_address'])): ?>
        <p><strong><?php echo t('Adresse :', 'Address:'); ?></strong> <?= $order['customer_address']; ?></p>
    <?php endif; ?>
    <p><strong><?php echo t('Moyen de paiement :', 'Payment Method:'); ?></strong> <?= $order['payment_method'] === 'cash' ? t('Espèces', 'Cash') : t('Carte bancaire', 'Credit / Debit Card'); ?></p>
    <h2 style="margin-top: 1.5rem;"><?php echo t('Articles :', 'Items:'); ?></h2>
    <ul style="list-style: none; padding-left: 0;">
        <?php foreach ($order['cart_items'] as $item): ?>
            <li style="margin-bottom: 0.5rem;">
                <?= $item['product_name']; ?> x<?= $item['quantity']; ?> – €<?= number_format($item['item_total'], 2); ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <p><strong><?php echo t('Sous-total :', 'Subtotal:'); ?></strong> €<?= number_format($order['subtotal'], 2); ?></p>
    <?php if ($order['delivery_fee'] > 0): ?>
        <p><strong><?php echo t('Frais de livraison :', 'Delivery Fee:'); ?></strong> €<?= number_format($order['delivery_fee'], 2); ?></p>
    <?php endif; ?>
    <p><strong><?php echo t('Total :', 'Total:'); ?></strong> €<?= number_format($order['total'], 2); ?></p>
    <div style="text-align:center; margin-top:2rem;">
        <a href="<?php echo base_url('cart/my_orders'); ?>" class="btn" style="display:inline-block; padding:0.75rem 1.5rem; background:#2d8cf0; color:#fff; border-radius:4px; text-decoration:none; font-weight:600;"><?php echo t('Voir mes commandes', 'View My Orders'); ?></a>
    </div>
</div>
