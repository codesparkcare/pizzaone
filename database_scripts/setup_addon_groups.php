<?php
/**
 * Pizza One - Addon Groups System Setup Guide
 * 
 * This file demonstrates how to set up addon groups for products
 * 
 * The system works in three steps:
 * 1. Create addon groups (e.g., "Extra Cheese", "Proteins")
 * 2. Add addons to the groups (e.g., Mozzarella, Cheddar, Cordon Bleu, Poulet)
 * 3. Link addon groups to products with min/max selection limits
 */

/*
DATABASE STRUCTURE:

addon_groups table:
- id: Primary key
- name: Group name (e.g., "Choose Extra Cheese")
- description: Optional group description

addon_group_items table:
- id: Primary key
- group_id: Links to addon_groups
- addon_id: Links to addons table

product_addon_groups table:
- id: Primary key
- product_id: Links to products
- group_id: Links to addon_groups
- min_selections: Minimum choices required (0, 1, etc.)
- max_selections: Maximum choices allowed (1, 2, 3, etc.)
- is_required: Whether this group is mandatory
- sort_order: Display order
*/

$mysqli = new mysqli("localhost", "root", "", "pizzaone");
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

echo "=== Pizza One - Addon Groups Management ===\n\n";

// ============================================================
// EXAMPLE 1: CREATE ADDON GROUP FOR CHEESES
// ============================================================
echo "1. Creating 'Extra Cheese' addon group...\n";

$group_data = [
    'name' => 'Choose if you want Extra adding',
    'description' => 'Select extra cheese toppings'
];

$query = "INSERT INTO addon_groups (name, description) VALUES (?, ?)
          ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $group_data['name'], $group_data['description']);
$stmt->execute();
$cheese_group_id = $stmt->insert_id;
echo "✓ Cheese group ID: $cheese_group_id\n\n";

// ============================================================
// EXAMPLE 2: CREATE ADDON GROUP FOR PROTEINS
// ============================================================
echo "2. Creating 'Choose anyone' addon group...\n";

$group_data2 = [
    'name' => 'Choose anyone',
    'description' => 'Select protein options'
];

$query = "INSERT INTO addon_groups (name, description) VALUES (?, ?)
          ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $group_data2['name'], $group_data2['description']);
$stmt->execute();
$protein_group_id = $stmt->insert_id;
echo "✓ Protein group ID: $protein_group_id\n\n";

// ============================================================
// EXAMPLE 3: CHECK IF ADDONS EXIST, CREATE IF NOT
// ============================================================
echo "3. Setting up cheese addons...\n";

$cheeses = [
    ['name' => 'Mozzarella', 'price' => 0.80],
    ['name' => 'Raclette', 'price' => 0.80],
    ['name' => 'Chevre', 'price' => 0.80],
    ['name' => 'Boursin', 'price' => 0.80],
    ['name' => 'Cheddar', 'price' => 0.80],
    ['name' => 'La vache qui rit', 'price' => 0.80],
    ['name' => 'Olives', 'price' => 0.50],
    ['name' => 'Oignons rouges', 'price' => 0.50],
    ['name' => 'Poivrons', 'price' => 0.50],
    ['name' => 'Champignons', 'price' => 0.50]
];

foreach ($cheeses as $cheese) {
    // Check if addon exists
    $check_query = "SELECT id FROM addons WHERE name = ?";
    $check_stmt = $mysqli->prepare($check_query);
    $check_stmt->bind_param("s", $cheese['name']);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        // Insert new addon
        $insert_query = "INSERT INTO addons (name, price, type) VALUES (?, ?, 'extra')";
        $insert_stmt = $mysqli->prepare($insert_query);
        $insert_stmt->bind_param("sd", $cheese['name'], $cheese['price']);
        $insert_stmt->execute();
        $addon_id = $insert_stmt->insert_id;
        echo "  ✓ Created: {$cheese['name']} (€{$cheese['price']})\n";
    } else {
        $row = $result->fetch_assoc();
        $addon_id = $row['id'];
        echo "  ✓ Found: {$cheese['name']}\n";
    }
    
    // Link to cheese group
    $link_query = "INSERT IGNORE INTO addon_group_items (group_id, addon_id) VALUES (?, ?)";
    $link_stmt = $mysqli->prepare($link_query);
    $link_stmt->bind_param("ii", $cheese_group_id, $addon_id);
    $link_stmt->execute();
}
echo "\n";

// ============================================================
// EXAMPLE 4: SET UP PROTEIN ADDONS
// ============================================================
echo "4. Setting up protein addons...\n";

$proteins = [
    ['name' => 'Cordon bleu', 'price' => 0.00],
    ['name' => 'Nuggets', 'price' => 0.00],
    ['name' => 'Poulet', 'price' => 0.00],
    ['name' => 'Kebab', 'price' => 0.00],
    ['name' => 'Tandoori', 'price' => 0.00],
    ['name' => 'Tenders', 'price' => 0.00],
    ['name' => 'Merguez', 'price' => 0.00]
];

foreach ($proteins as $protein) {
    $check_query = "SELECT id FROM addons WHERE name = ?";
    $check_stmt = $mysqli->prepare($check_query);
    $check_stmt->bind_param("s", $protein['name']);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        $insert_query = "INSERT INTO addons (name, price, type) VALUES (?, ?, 'protein')";
        $insert_stmt = $mysqli->prepare($insert_query);
        $insert_stmt->bind_param("sd", $protein['name'], $protein['price']);
        $insert_stmt->execute();
        $addon_id = $insert_stmt->insert_id;
        echo "  ✓ Created: {$protein['name']}\n";
    } else {
        $row = $result->fetch_assoc();
        $addon_id = $row['id'];
        echo "  ✓ Found: {$protein['name']}\n";
    }
    
    $link_query = "INSERT IGNORE INTO addon_group_items (group_id, addon_id) VALUES (?, ?)";
    $link_stmt = $mysqli->prepare($link_query);
    $link_stmt->bind_param("ii", $protein_group_id, $addon_id);
    $link_stmt->execute();
}
echo "\n";

// ============================================================
// EXAMPLE 5: LINK ADDON GROUPS TO A PRODUCT
// ============================================================
echo "5. Linking addon groups to products...\n";

// Find a product (e.g., product with ID 1)
$product_query = "SELECT id, name FROM products LIMIT 1";
$result = $mysqli->query($product_query);
if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
    $product_id = $product['id'];
    
    echo "  Linking groups to product: {$product['name']} (ID: $product_id)\n\n";
    
    // Link cheese group (optional, max 3 selections)
    $link_query = "INSERT INTO product_addon_groups 
                   (product_id, group_id, min_selections, max_selections, is_required, sort_order) 
                   VALUES (?, ?, 0, 3, 0, 1)
                   ON DUPLICATE KEY UPDATE 
                   min_selections=0, max_selections=3, is_required=0, sort_order=1";
    $stmt = $mysqli->prepare($link_query);
    $stmt->bind_param("ii", $product_id, $cheese_group_id);
    $stmt->execute();
    echo "  ✓ Linked: 'Extra Cheese' group (max 3 selections)\n";
    
    // Link protein group (optional, max 2 selections)
    $link_query = "INSERT INTO product_addon_groups 
                   (product_id, group_id, min_selections, max_selections, is_required, sort_order) 
                   VALUES (?, ?, 0, 2, 0, 2)
                   ON DUPLICATE KEY UPDATE 
                   min_selections=0, max_selections=2, is_required=0, sort_order=2";
    $stmt = $mysqli->prepare($link_query);
    $stmt->bind_param("ii", $product_id, $protein_group_id);
    $stmt->execute();
    echo "  ✓ Linked: 'Protein' group (max 2 selections)\n";
}

echo "\n✓ Setup complete!\n\n";

echo "=== To Link Groups to Other Products ===\n";
echo "SQL Example:\n";
echo "INSERT INTO product_addon_groups (product_id, group_id, min_selections, max_selections, is_required, sort_order)\n";
echo "VALUES (PRODUCT_ID, GROUP_ID, MIN, MAX, IS_REQUIRED, SORT_ORDER);\n\n";

echo "Parameters:\n";
echo "- min_selections: 0 = optional, 1+ = required minimum choices\n";
echo "- max_selections: How many items customer can select\n";
echo "- is_required: true = group shows with red asterisk, false = optional\n";
echo "- sort_order: Display order (1, 2, 3, etc.)\n";

$mysqli->close();
?>
