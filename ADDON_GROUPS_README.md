# Pizza One - Dynamic Addon Groups System

## Quick Start Guide

### Step 1: Set Up Database Tables
Run the database setup script to create the necessary tables:
```
http://localhost/pizzaone/create_addon_groups_table.php
```

You should see:
- ✓ Table addon_groups created successfully
- ✓ Table addon_group_items created successfully  
- ✓ Table product_addon_groups created successfully

### Step 2: Create Addon Groups & Link to Products
Run the setup script to create example addon groups:
```
http://localhost/pizzaone/setup_addon_groups.php
```

This will:
1. Create "Choose Extra Cheese" group with 10 cheese options
2. Create "Choose anyone" group with 7 protein options
3. Link both groups to the first product (product ID 1)
4. Show you the IDs for reference

### Step 3: Test on a Product
Visit a product in your store to see the addon groups:
```
http://localhost/pizzaone/cart/add/1
```

You should see:
- ✓ "Choose Extra Cheese" section with up to 3 cheese options selectable
- ✓ "Choose anyone" section with up to 2 protein options selectable
- ✓ Price updates dynamically as you select addons
- ✓ Options to prevent exceeding max selections

---

## System Overview

### Three-Tier Architecture

```
Products
    ↓
Product Addon Groups (links products to addon groups with settings)
    ↓
Addon Groups (named groups like "Cheeses", "Proteins")
    ↓
Addon Group Items (links addons to groups)
    ↓
Addons (individual items like "Mozzarella", "Cheddar", etc.)
```

### Configuration Options

For each addon group linked to a product:

| Option | Values | Example | Meaning |
|--------|--------|---------|---------|
| **min_selections** | 0, 1, 2, 3,4... | 0 | Optional (0 means customer doesn't have to choose) |
| **max_selections** | 1, 2, 3, 4... | 3 | Customer can select UP TO 4 items from this group |
| **is_required** | true/false | false | Whether group shows with red asterisk |
| **sort_order** | 1, 2, 3,4... | 1 | Display order (1 = first, 2 = second, etc.) |

---

## How to Add Addon Groups to Your Products

### Method 1: Using SQL (Direct)

#### Create a New Addon Group
```sql
INSERT INTO addon_groups (name, description)
VALUES ('Choose Sauce Base', 'Select your sauce base');
```

#### Add Addons to the Group
First, check if addons exist or create them:
```sql
INSERT INTO addons (name, price, type)
VALUES ('Tomato Sauce', 0.00, 'sauce'),
       ('Cream Base', 0.50, 'sauce'),
       ('Pesto', 0.80, 'sauce')
ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);
```

Then link them to the group:
```sql
INSERT IGNORE INTO addon_group_items (group_id, addon_id)
SELECT 3, id FROM addons WHERE name IN ('Tomato Sauce', 'Cream Base', 'Pesto');
```

#### Link the Group to a Product
```sql
INSERT INTO product_addon_groups 
(product_id, group_id, min_selections, max_selections, is_required, sort_order)
VALUES (15, 3, 1, 1, 1, 1);
-- Product 15, Group 3, min 1, max 1 (required, single choice), required, order 1
```

### Method 2: Using phpMyAdmin

1. Go to `addon_groups` table → Insert → Create your group
2. Go to `addons` table → Find or create the addons you need
3. Go to `addon_group_items` table → Insert rows to link addons to groups
4. Go to `product_addon_groups` table → Insert rows to link groups to products with settings

### Method 3: Using the PHP Setup Script

Edit `setup_addon_groups.php` to add your own addon groups:

```php
$cheeses = [
    ['name' => 'Mozzarella', 'price' => 0.80],
    ['name' => 'Cheddar', 'price' => 0.80],
    // ... add more
];

// Then link to product:
$link_query = "INSERT INTO product_addon_groups 
               (product_id, group_id, min_selections, max_selections, is_required, sort_order) 
               VALUES (?, ?, 0, 3, 0, 1)";
```

---

## Real-World Examples

### Example 1: Pizza with Optional Toppings (Max 3)
```sql
-- Add group for extra toppings
INSERT INTO addon_groups (name) VALUES ('Extra Toppings');

-- Link to Pizza product (ID 5)
INSERT INTO product_addon_groups 
(product_id, group_id, min_selections, max_selections, is_required, sort_order)
VALUES (5, 1, 0, 3, 0, 1);
-- 0 min = optional, 3 max = choose up to 3, not required
```

### Example 2: Burger with Mandatory Sauce (Choose 1)
```sql
-- Add group for sauce selection
INSERT INTO addon_groups (name) VALUES ('Choose Sauce');

-- Link to Burger product (ID 8)
INSERT INTO product_addon_groups 
(product_id, group_id, min_selections, max_selections, is_required, sort_order)
VALUES (8, 2, 1, 1, 1, 1);
-- 1 min = required, 1 max = must choose exactly 1, is required
```

### Example 3: Sandwich with Cheese & Protein Options
```sql
-- Add two groups
INSERT INTO addon_groups (name) VALUES ('Choose Cheese'), ('Choose Protein');

-- Link both to Sandwich product (ID 12)
INSERT INTO product_addon_groups (product_id, group_id, min_selections, max_selections, is_required, sort_order)
VALUES 
  (12, 3, 0, 2, 0, 1),  -- Cheese: optional, max 2, order 1
  (12, 4, 0, 1, 0, 2);  -- Protein: optional, max 1, order 2
```

---

## Frontend Display

### What Customers See

When addon groups are configured:

```
╔════════════════════════════════════════╗
║  Choose if you want Extra adding       ║
║  Choose up to 3                        ║
╠════════════════════════════════════════╣
║ ☐ Mozzarella          +€0.80           ║
║ ☐ Raclette            +€0.80           ║
║ ☐ Cheddar             +€0.80           ║
║ ☐ Olives              +€0.50           ║
╠════════════════════════════════════════╣
║  Choose anyone *                       ║
║  Choose up to 2                        ║
╠════════════════════════════════════════╣
║ ☐ Cordon bleu         +€0.00           ║
║ ☐ Poulet              +€0.00           ║
║ ☐ Kebab               +€0.00           ║
╚════════════════════════════════════════╝
```

### Smart Validation

- Prevents selecting more than max_selections
- Shows "Choose up to X" hint
- Disables unchecked options when max reached
- Visual feedback with disabled styling

---

## Technical Details

### Database Schema

**addon_groups**
```
id INT PRIMARY KEY AUTO_INCREMENT
name VARCHAR(255) NOT NULL
description TEXT
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
```

**addon_group_items**
```
id INT PRIMARY KEY AUTO_INCREMENT
group_id INT NOT NULL (FK to addon_groups)
addon_id INT NOT NULL (FK to addons)
UNIQUE(group_id, addon_id)
```

**product_addon_groups**
```
id INT PRIMARY KEY AUTO_INCREMENT
product_id INT NOT NULL (FK to products)
group_id INT NOT NULL (FK to addon_groups)
min_selections INT DEFAULT 0
max_selections INT DEFAULT 1
is_required BOOLEAN DEFAULT FALSE
sort_order INT DEFAULT 0
UNIQUE(product_id, group_id)
```

### PHP Model Methods

**Common_model.php:**
```php
// Get all addon groups for a product with their items
get_addon_groups_by_product($product_id)

// Get all addons in a specific group
get_addons_in_group($group_id)
```

### JavaScript Functions

**add_to_cart.php:**
```javascript
// Validate addon group selections
validateAddonGroup(checkbox)
  - Enforces max_selections limit
  - Disables options when max reached
  - Shows alert if limit exceeded

// Update price with addon selections
updatePrice()
  - Calculates both old and new addon system prices
  - Updates total display
```

---

## Troubleshooting

### Addon Groups Not Showing?
1. ✓ Verify `product_addon_groups` rows exist for your product
2. ✓ Verify `addon_group_items` rows link the group to addons
3. ✓ Check browser console for JavaScript errors
4. ✓ Verify addons have prices set

### Can't Select More Than Expected?
1. ✓ Check `max_selections` value in `product_addon_groups`
2. ✓ Increase the limit if needed
3. ✓ Reload page after database change

### Prices Not Updating?
1. ✓ Verify addon `price` field is set in database
2. ✓ Check JavaScript console for errors
3. ✓ Try clearing browser cache

### Backward Compatibility Issues?
✓ Old addon system (`product_addons` table) still works alongside new system
✓ Products can use old, new, or both systems
✓ No breaking changes

---

## Support

For detailed technical information, see the source files:
- [create_addon_groups_table.php](create_addon_groups_table.php) - Database setup
- [setup_addon_groups.php](setup_addon_groups.php) - Example setup
- [application/models/Common_model.php](application/models/Common_model.php) - Database queries
- [application/controllers/Cart.php](application/controllers/Cart.php) - Cart logic
- [application/views/add_to_cart.php](application/views/add_to_cart.php) - Frontend display

