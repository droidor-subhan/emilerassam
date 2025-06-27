<?php
class ModelCatalogProductStock extends Model
{

    public function getAllProducts()
    {
        $query = $this->db->query("
        SELECT DISTINCT 
            p.product_id,
            COALESCE(pd.name, '') as product_name,
            p.status
        FROM " . DB_PREFIX . "product p
        LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "')
        LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (p.product_id = pov.product_id)
        LEFT JOIN " . DB_PREFIX . "option_description size_od ON (pov.option_id = size_od.option_id AND size_od.language_id = '" . (int)$this->config->get('config_language_id') . "')
        WHERE 
            COALESCE(size_od.name, '') = 'Sizes'
            AND p.status = 1
        ORDER BY pd.name ASC
    ");

        $products = array();

        if ($query->num_rows > 0) {
            foreach ($query->rows as $row) {
                $products[] = array(
                    'product_id' => $row['product_id'],
                    'product_name' => $row['product_name']
                );
            }
        }

        return $products;
    }

    public function getProductStockData($selected_products = array())
    {
        // Build WHERE clause for selected products
        $product_filter = '';
        if (!empty($selected_products) && is_array($selected_products)) {
            $product_ids = array_map('intval', $selected_products);
            $product_filter = " AND p.product_id IN (" . implode(',', $product_ids) . ")";
        }

        $query = $this->db->query("
        SELECT 
            p.product_id,
            COALESCE(pd.name, '') as product_name,
            COALESCE(size_ovd.name, '') as size_name,
            COALESCE(color_ovd.name, '') as color_name,
            JSON_UNQUOTE(JSON_EXTRACT(pov.colors_selection_array, CONCAT('$[', numbers.n, '].size_selection_value'))) AS size_color_quantity,
            pov.quantity as size_total_quantity,
            pov.product_option_value_id,
            JSON_UNQUOTE(JSON_EXTRACT(pov.colors_selection_array, CONCAT('$[', numbers.n, '].size_selection_id'))) AS color_option_value_id
        FROM " . DB_PREFIX . "product p
        LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "')
        
        -- Get Size options from product_option_value
        LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (p.product_id = pov.product_id)
        LEFT JOIN " . DB_PREFIX . "option_description size_od ON (pov.option_id = size_od.option_id AND size_od.language_id = '" . (int)$this->config->get('config_language_id') . "')
        LEFT JOIN " . DB_PREFIX . "option_value_description size_ovd ON (pov.option_value_id = size_ovd.option_value_id AND size_ovd.language_id = '" . (int)$this->config->get('config_language_id') . "')
        
        -- Cross join with numbers to extract JSON array elements
        CROSS JOIN (
            SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 
            UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
        ) numbers
        
        -- Get color names from the JSON array
        LEFT JOIN " . DB_PREFIX . "option_value_description color_ovd ON (
            JSON_UNQUOTE(JSON_EXTRACT(pov.colors_selection_array, CONCAT('$[', numbers.n, '].size_selection_id'))) = color_ovd.option_value_id
            AND color_ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'
        )
        
        WHERE 
            -- Filter for Size options only
            COALESCE(size_od.name, '') = 'Sizes'
            -- Ensure the JSON array element exists
            AND JSON_EXTRACT(pov.colors_selection_array, CONCAT('$[', numbers.n, ']')) IS NOT NULL
            -- Filter for valid sizes
            AND COALESCE(size_ovd.name, '') IN ('One Size', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', 'UK6|EU34', 'UK8|EU36', 'UK10|EU38', 'UK12|EU40', 'UK12|EU42', 'UK14|EU42', 'UK16|EU44', 'UK18|EU46', 'UK20|EU48', 'UK30|EU40', 'UK32|EU42', 'UK34|EU44', 'UK36|EU46', 'UK38|EU48', 'UK40|EU50', 'UK42|EU52', 'UK44|EU54', '34', '36', '38', '40', '42', '44', '46', '48', '50', '52', '54', '56', '58')
            -- Filter for valid colors
            AND COALESCE(color_ovd.name, '') IN ('Black', 'Navy Blue', 'Light Grey', 'White', 'Beige', 'Sky Blue', 'Denim', 'Brown', 'Turquoise', 'Grey', 'Red', 'Off White', 'GG Beige', 'GG Navy Blue', 'Mole', 'Royal Blue', 'Gold', 'Aqua', 'Plum', 'Mauve', 'Lilac', 'Forest Green', 'Bordeaux', 'Azure', 'Orange', 'Dark Grey', 'Green Apple', 'Mustard/Navy Blue', 'Red/Navy Blue', 'Green/Navy Blue', 'Grey/Navy Blue', 'Beige/White-Off', 'Navy Blue/Azure', 'Navy Blue/White-Off', 'Grey/White-Off', 'Navy Blue/White', 'Red/White')
            -- Only include combinations with stock > 0
            AND CAST(JSON_UNQUOTE(JSON_EXTRACT(pov.colors_selection_array, CONCAT('$[', numbers.n, '].size_selection_value'))) AS UNSIGNED) > 0
            " . $product_filter . "
        
        ORDER BY p.product_id, size_ovd.name, color_ovd.name
    ");

        $data = array();

        if ($query->num_rows > 0) {
            foreach ($query->rows as $row) {
                $data[] = array(
                    'product_id' => $row['product_id'],
                    'product_name' => $row['product_name'],
                    'size' => $row['size_name'],
                    'color' => $row['color_name'],
                    'size_quantity' => $row['size_total_quantity'],
                    'total_quantity' => $row['size_color_quantity'],
                    'available_quantity' => $row['size_color_quantity'],
                    'size_pov_id' => $row['product_option_value_id'],
                    'color_pov_id' => $row['color_option_value_id']
                );
            }
        }

        return $data;
    }
    
    public function importProductStockData($csv_data)
    {
        $success_count = 0;
        $error_count = 0;
        $errors = array();
        $processed_products = array();
        $transaction_started = false;

        try {
            $this->db->query("SET TRANSACTION ISOLATION LEVEL READ COMMITTED");
            $this->db->query("START TRANSACTION");
            $transaction_started = true;

            if (empty($csv_data) || !is_array($csv_data)) {
                throw new Exception('Invalid CSV data provided');
            }

            $headers = $csv_data[0];
            if (!empty($headers[0])) {
                $headers[0] = str_replace(["\xEF\xBB\xBF", "\xFF\xFE", "\xFE\xFF", "\x00\x00\xFE\xFF", "\xFF\xFE\x00\x00"], '', $headers[0]);
                $headers[0] = preg_replace('/^[\x00-\x1F\x7F-\xFF]+|[\x00-\x1F\x7F-\xFF]+$/', '', $headers[0]);
                $headers[0] = trim($headers[0], "\"\'\x00..\x1F\x7F..\xFF");
            }

            $headers = array_map(function($header) {
                $header = str_replace(["\xEF\xBB\xBF", "\xFF\xFE", "\xFE\xFF"], '', $header);
                $header = preg_replace('/^[\x00-\x1F\x7F-\xFF]+|[\x00-\x1F\x7F-\xFF]+$/', '', $header);
                $header = trim($header, "\"\'\x00..\x1F\x7F..\xFF");
                return strtolower(trim($header));
            }, $headers);

            $required_headers = ['product id', 'size', 'color', 'size-color quantity'];
            foreach ($required_headers as $required_header) {
                if (array_search($required_header, $headers) === false) {
                    throw new Exception("Required header '{$required_header}' not found in CSV");
                }
            }

            $product_id_pos = array_search('product id', $headers);
            $size_pos = array_search('size', $headers);
            $color_pos = array_search('color', $headers);
            $quantity_pos = array_search('size-color quantity', $headers);

            foreach ($csv_data as $index => $row) {
                if ($index == 0) continue;
                $savepoint_name = "sp_row_" . $index;

                try {
                    $this->db->query("SAVEPOINT " . $savepoint_name);

                    if (count($row) < count($required_headers)) {
                        throw new Exception("Insufficient columns in row");
                    }

                    $product_id = (int)trim($row[$product_id_pos]);
                    $size_name = trim($row[$size_pos]);
                    $color_name = trim($row[$color_pos]);
                    $quantity_raw = trim($row[$quantity_pos]);

                    if ($product_id <= 0) {
                        throw new Exception("Invalid product ID: {$product_id}");
                    }

                    if (empty($size_name)) {
                        throw new Exception("Size cannot be empty");
                    }

                    if (empty($color_name)) {
                        throw new Exception("Color cannot be empty");
                    }

                    $update_quantity = false;
                    if ($quantity_raw !== '') {
                        if (!is_numeric($quantity_raw) || $quantity_raw < 0) {
                            throw new Exception("Invalid quantity: must be a non-negative number or left empty");
                        }
                        $size_color_quantity = (float)$quantity_raw;
                        $update_quantity = true;
                    }

                    $lock_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "' FOR UPDATE");
                    if ($lock_query->num_rows == 0) {
                        throw new Exception("Product ID {$product_id} does not exist");
                    }

                    $size_query = $this->db->query("SELECT pov.product_option_value_id, pov.colors_selection_array FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_description od ON (pov.option_id = od.option_id AND od.language_id = '" . (int)$this->config->get('config_language_id') . "') LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "') WHERE pov.product_id = '" . (int)$product_id . "' AND od.name = 'Sizes' AND ovd.name = '" . $this->db->escape($size_name) . "' FOR UPDATE");

                    if ($size_query->num_rows == 0) {
                        throw new Exception("Size option not found for product ID {$product_id}, size {$size_name}");
                    }

                    $size_data = $size_query->row;
                    $colors_selection_array = json_decode($size_data['colors_selection_array'], true);
                    if (!is_array($colors_selection_array)) {
                        $colors_selection_array = array();
                    }

                    $color_query = $this->db->query("SELECT option_value_id FROM " . DB_PREFIX . "option_value_description WHERE name = '" . $this->db->escape($color_name) . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
                    if ($color_query->num_rows == 0) {
                        throw new Exception("Color option not found: {$color_name}");
                    }

                    $color_option_value_id = $color_query->row['option_value_id'];
                    $updated = false;

                    for ($i = 0; $i < count($colors_selection_array); $i++) {
                        if (isset($colors_selection_array[$i]['size_selection_id']) &&
                            $colors_selection_array[$i]['size_selection_id'] == $color_option_value_id) {
                            if ($update_quantity) {
                                $colors_selection_array[$i]['size_selection_value'] = $size_color_quantity;
                            }
                            $updated = true;
                            break;
                        }
                    }

                    if (!$updated && $update_quantity) {
                        $colors_selection_array[] = array(
                            'size_selection_id' => $color_option_value_id,
                            'size_selection_value' => $size_color_quantity
                        );
                    }

                    if ($updated || $update_quantity) {
                        $update_result = $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET colors_selection_array = '" . $this->db->escape(json_encode($colors_selection_array)) . "' WHERE product_option_value_id = '" . (int)$size_data['product_option_value_id'] . "'");
                        if (!$update_result) {
                            throw new Exception("Failed to update colors_selection_array");
                        }
                        $processed_products[$product_id] = true;
                    }

                    $success_count++;
                    $this->db->query("RELEASE SAVEPOINT " . $savepoint_name);

                } catch (Exception $e) {
                    $this->db->query("ROLLBACK TO SAVEPOINT " . $savepoint_name);
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                    $error_count++;
                    continue;
                }
            }

            foreach ($processed_products as $product_id => $value) {
                try {
                    $recalc_savepoint = "sp_recalc_" . $product_id;
                    $this->db->query("SAVEPOINT " . $recalc_savepoint);

                    $recalc_result = $this->recalculateProductQuantities($product_id);
                    if (!$recalc_result['success']) {
                        throw new Exception($recalc_result['error']);
                    }
                    $this->db->query("RELEASE SAVEPOINT " . $recalc_savepoint);
                } catch (Exception $e) {
                    $this->db->query("ROLLBACK TO SAVEPOINT " . $recalc_savepoint);
                    $errors[] = "Product ID {$product_id} recalculation: " . $e->getMessage();
                    $error_count++;
                }
            }

            if ($success_count > 0) {
                $this->db->query("COMMIT");
                $transaction_started = false;
                $this->log->write("ProductStock Import: Transaction committed - Success: {$success_count}, Errors: {$error_count}");
            } else {
                $this->db->query("ROLLBACK");
                $transaction_started = false;
                $this->log->write("ProductStock Import: Transaction rolled back - No successful imports");
            }

            return array(
                'success_count' => $success_count,
                'error_count' => $error_count,
                'errors' => $errors,
                'transaction_committed' => $success_count > 0
            );

        } catch (Exception $e) {
            if ($transaction_started) {
                $this->db->query("ROLLBACK");
                $this->log->write("ProductStock Import: Transaction rolled back due to exception - " . $e->getMessage());
            }
            return array(
                'success_count' => 0,
                'error_count' => 1,
                'errors' => array('Transaction failed: ' . $e->getMessage()),
                'transaction_committed' => false
            );
        }
    }

    public function validateProductStockData($csv_data)
    {
        $valid_count = 0;
        $error_count = 0;
        $errors = array();

        try {
            // Validate CSV structure
            if (empty($csv_data) || !is_array($csv_data)) {
                return array(
                    'valid_count' => 0,
                    'error_count' => 1,
                    'errors' => array('Invalid CSV data provided')
                );
            }

            // Get header positions (case-insensitive)
            // Remove BOM from first header if present and normalize headers
            $headers = $csv_data[0];
            if (!empty($headers[0])) {
                // Remove various BOM characters more comprehensively
                $headers[0] = str_replace(["\xEF\xBB\xBF", "\xFF\xFE", "\xFE\xFF", "\x00\x00\xFE\xFF", "\xFF\xFE\x00\x00"], '', $headers[0]);
                // Remove all non-printable characters from beginning and end
                $headers[0] = preg_replace('/^[\x00-\x1F\x7F-\xFF]+|[\x00-\x1F\x7F-\xFF]+$/', '', $headers[0]);
                // Remove any remaining quotes or special characters
                $headers[0] = trim($headers[0], "\"\'\x00..\x1F\x7F..\xFF");
            }
            
            $headers = array_map(function($header) {
                // Clean each header thoroughly
                $header = str_replace(["\xEF\xBB\xBF", "\xFF\xFE", "\xFE\xFF"], '', $header);
                $header = preg_replace('/^[\x00-\x1F\x7F-\xFF]+|[\x00-\x1F\x7F-\xFF]+$/', '', $header);
                $header = trim($header, "\"\'\x00..\x1F\x7F..\xFF");
                return strtolower(trim($header));
            }, $headers);
            $required_headers = ['product id', 'size', 'color', 'size-color quantity'];

            foreach ($required_headers as $required_header) {
                if (array_search($required_header, $headers) === false) {
                    return array(
                        'valid_count' => 0,
                        'error_count' => 1,
                        'errors' => array("Required header '{$required_header}' not found in CSV")
                    );
                }
            }

            $product_id_pos = array_search('product id', $headers);
            $size_pos = array_search('size', $headers);
            $color_pos = array_search('color', $headers);
            $quantity_pos = array_search('size-color quantity', $headers);

            foreach ($csv_data as $index => $row) {
                // Skip header row
                if ($index == 0) continue;

                try {
                    // Check row structure
                    if (count($row) < count($required_headers)) {
                        throw new Exception("Insufficient columns in row");
                    }

                    $product_id = trim($row[$product_id_pos]);
                    $size_name = trim($row[$size_pos]);
                    $color_name = trim($row[$color_pos]);
                    $size_color_quantity = trim($row[$quantity_pos]);

                    // Validate data types and ranges
                    if (!is_numeric($product_id) || (int)$product_id <= 0) {
                        throw new Exception("Invalid product ID: {$product_id}");
                    }

                    if (empty($size_name)) {
                        throw new Exception("Size cannot be empty");
                    }

                    if (empty($color_name)) {
                        throw new Exception("Color cannot be empty");
                    }

                    if (!is_numeric($size_color_quantity) || (int)$size_color_quantity < 0) {
                        throw new Exception("Quantity must be a non-negative number: {$size_color_quantity}");
                    }

                    $product_id = (int)$product_id;

                    // Check if product exists
                    $product_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
                    if ($product_query->num_rows == 0) {
                        throw new Exception("Product ID {$product_id} does not exist");
                    }

                    // Check if size option exists for this product
                    $size_query = $this->db->query("
                        SELECT 1
                        FROM " . DB_PREFIX . "product_option_value pov
                        LEFT JOIN " . DB_PREFIX . "option_description od ON (pov.option_id = od.option_id AND od.language_id = '" . (int)$this->config->get('config_language_id') . "')
                        LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "')
                        WHERE pov.product_id = '" . (int)$product_id . "'
                        AND od.name = 'Sizes'
                        AND ovd.name = '" . $this->db->escape($size_name) . "'
                    ");

                    if ($size_query->num_rows == 0) {
                        throw new Exception("Size '{$size_name}' not found for product ID {$product_id}");
                    }

                    // Check if color option exists
                    $color_query = $this->db->query("
                        SELECT option_value_id
                        FROM " . DB_PREFIX . "option_value_description
                        WHERE name = '" . $this->db->escape($color_name) . "'
                        AND language_id = '" . (int)$this->config->get('config_language_id') . "'
                    ");

                    if ($color_query->num_rows == 0) {
                        throw new Exception("Color '{$color_name}' does not exist");
                    }

                    $valid_count++;

                } catch (Exception $e) {
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                    $error_count++;
                }
            }

        } catch (Exception $e) {
            return array(
                'valid_count' => 0,
                'error_count' => 1,
                'errors' => array('Validation failed: ' . $e->getMessage())
            );
        }

        return array(
            'valid_count' => $valid_count,
            'error_count' => $error_count,
            'errors' => $errors
        );
    }

    private function recalculateProductQuantities($product_id)
    {
        try {
            // Validate product ID
            if (!is_numeric($product_id) || (int)$product_id <= 0) {
                throw new Exception("Invalid product ID: {$product_id}");
            }

            $product_id = (int)$product_id;

            // Reset all color option quantities to 0 first
            $reset_result = $this->db->query("
                UPDATE " . DB_PREFIX . "product_option_value 
                SET quantity = 0 
                WHERE product_id = '" . $product_id . "' 
                AND option_id = 14
            ");

            if (!$reset_result) {
                throw new Exception("Failed to reset color option quantities");
            }

            // Get all size options with their color combinations
            $size_options_query = $this->db->query("
                SELECT product_option_value_id, colors_selection_array
                FROM " . DB_PREFIX . "product_option_value
                WHERE product_id = '" . $product_id . "'
                AND option_id = 15
            ");

            $total_quantity = 0;

            foreach ($size_options_query->rows as $size_option) {
                $colors_selection_array = json_decode($size_option['colors_selection_array'], true);

                if (is_array($colors_selection_array)) {
                    $size_total = 0;

                    foreach ($colors_selection_array as $color_combination) {
                        if (isset($color_combination['size_selection_id']) &&
                            isset($color_combination['size_selection_value']) &&
                            is_numeric($color_combination['size_selection_value']) &&
                            $color_combination['size_selection_value'] > 0) {

                            $color_id = (int)$color_combination['size_selection_id'];
                            $color_quantity = (int)$color_combination['size_selection_value'];

                            if ($color_id > 0 && $color_quantity >= 0) {
                                $size_total += $color_quantity;

                                // Update individual color option quantity
                                $color_update_result = $this->db->query("
                                    UPDATE " . DB_PREFIX . "product_option_value 
                                    SET quantity = quantity + '" . $color_quantity . "'
                                    WHERE product_id = '" . $product_id . "' 
                                    AND option_value_id = '" . $color_id . "'
                                    AND option_id = 14
                                ");

                                if (!$color_update_result) {
                                    throw new Exception("Failed to update color option quantity for color ID {$color_id}");
                                }
                            }
                        }
                    }

                    // Update size option quantity
                    $size_update_result = $this->db->query("
                        UPDATE " . DB_PREFIX . "product_option_value 
                        SET quantity = '" . $size_total . "'
                        WHERE product_option_value_id = '" . (int)$size_option['product_option_value_id'] . "'
                    ");

                    if (!$size_update_result) {
                        throw new Exception("Failed to update size option quantity for POV ID {$size_option['product_option_value_id']}");
                    }

                    $total_quantity += $size_total;
                }
            }

            // Update main product quantity
            $product_update_result = $this->db->query("
                UPDATE " . DB_PREFIX . "product 
                SET quantity = '" . $total_quantity . "' 
                WHERE product_id = '" . $product_id . "'
            ");

            if (!$product_update_result) {
                throw new Exception("Failed to update main product quantity");
            }

            return array(
                'success' => true,
                'total_quantity' => $total_quantity
            );

        } catch (Exception $e) {
            return array(
                'success' => false,
                'error' => "Recalculation failed for product {$product_id}: " . $e->getMessage()
            );
        }
    }
    
}