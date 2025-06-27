<?php
class ControllerCatalogProductStock extends Controller
{

    public function index()
    {
        $this->load->language('catalog/product_stock');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_export'] = $this->language->get('text_export');
        $data['text_import'] = $this->language->get('text_import');

        $data['button_export'] = $this->language->get('button_export');
        $data['button_import'] = $this->language->get('button_import');

        $data['export_url'] = $this->url->link('catalog/product_stock/export', 'user_token=' . $this->session->data['user_token'], true);
        $data['import_url'] = $this->url->link('catalog/product_stock/import', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/product_stock', $data));
    }

    public function export()
    {
        $this->load->language('catalog/product_stock');
        $this->load->model('catalog/product_stock');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            // Get selected product IDs from POST data
            $selected_products = isset($this->request->post['selected_products']) ? $this->request->post['selected_products'] : array();

            // If no products selected, export all products (pass empty array to get all)
            if (empty($selected_products)) {
                $selected_products = array(); // This will trigger export of all products
            }

            // Get the data from model with selected products filter
            $data = $this->model_catalog_product_stock->getProductStockData($selected_products);

            $filename = 'product_stock_' . date('Y-m-d_H-i-s') . '.csv';

            // Set headers for CSV download
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');

            // Clean any previous output
            if (ob_get_level()) {
                ob_end_clean();
            }

            // Open output stream
            $output = fopen('php://output', 'w');

            // Add BOM for UTF-8 (helps with Excel compatibility)
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV Headers
            fputcsv($output, array(
                'Product ID',
                'Product name',
                'Size',
                'Color',
                'Size-Color quantity',
            ));

            // Write data rows if any exist
            if (!empty($data)) {
                foreach ($data as $row) {
                    fputcsv($output, array(
                        isset($row['product_id']) ? $row['product_id'] : '',
                        isset($row['product_name']) ? $row['product_name'] : '',
                        isset($row['size']) ? $row['size'] : '',
                        isset($row['color']) ? $row['color'] : '',
                        isset($row['total_quantity']) ? $row['total_quantity'] : ''
                    ));
                }
            }

            fclose($output);
            exit(); // Important: exit after sending file
        }

        // If not POST request, show export form
        $this->document->setTitle($this->language->get('heading_title_export'));

        // Get all available products for the dropdown
        $data['products'] = $this->model_catalog_product_stock->getAllProducts();

        $data['heading_title'] = $this->language->get('heading_title_export');
        $data['text_export_description'] = 'Export product stock data to CSV format.';
        $data['button_export'] = $this->language->get('button_export') ?: 'Export CSV';
        $data['button_cancel'] = $this->language->get('button_cancel') ?: 'Cancel';

        // Add breadcrumbs
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => 'Catalog',
            'href' => $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => 'Product Stock',
            'href' => $this->url->link('catalog/product_stock', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => 'Export Product Stock',
            'href' => $this->url->link('catalog/product_stock/export', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('catalog/product_stock/export', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('catalog/product_stock', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/product_stock_export', $data));
    }

    public function import()
    {
        $this->load->language('catalog/product_stock');

        // Not logged in → redirect to login page
        if (!$this->user->isLogged() || !isset($this->session->data['user_token'])) {
            $this->response->redirect($this->url->link('common/login', '', true));
            return;
        }

        // POST = AJAX import/validate
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $json = array();

            // Validate token
            if (!isset($this->request->get['user_token']) ||
                ($this->request->get['user_token'] != $this->session->data['user_token'])) {
                $json['error'] = 'Invalid token. Please refresh and try again.';
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }

            // Validate file upload
            if (empty($this->request->files) || !isset($this->request->files['import_file']) || $this->request->files['import_file']['error'] != UPLOAD_ERR_OK) {
                $json['error'] = 'Please select a valid CSV file to upload.';
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }

            $file = $this->request->files['import_file'];
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if ($file_extension !== 'csv') {
                $json['error'] = 'Please upload a CSV file only.';
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }

            if ($file['size'] > 5242880) {
                $json['error'] = 'File size exceeds the maximum limit of 5MB.';
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }

            // Read CSV
            $csv_data = array();
            if (($handle = fopen($file['tmp_name'], 'r')) !== false) {
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $csv_data[] = $data;
                }
                fclose($handle);
            }

            if (empty($csv_data)) {
                $json['error'] = 'CSV file is empty or could not be read.';
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }

            // Validate CSV structure and headers
            $validation_result = $this->validateCSVStructure($csv_data);
            if (!$validation_result['valid']) {
                $json['error'] = $validation_result['error'];
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }

            // Process CSV
            try {
                $this->load->model('catalog/product_stock');
                $validate_only = isset($this->request->post['validate_only']) && $this->request->post['validate_only'] == '1';

                if ($validate_only) {
                    $result = $this->model_catalog_product_stock->validateProductStockData($csv_data);

                    if ($result['error_count'] > 0) {
                        $json['warning'] = sprintf(
                            'Validation found %d errors in %d rows. %s',
                            $result['error_count'],
                            count($csv_data) - 1,
                            implode('; ', array_slice($result['errors'], 0, 5))
                        );
                    } else {
                        $json['success'] = sprintf('Validation passed! %d rows are ready to import.', $result['valid_count']);
                    }
                } else {
                    // Check for zero quantities and get confirmation
                    $zero_quantity_check = $this->checkZeroQuantities($csv_data);
                    $confirm_zeros = isset($this->request->post['confirm_zeros']) && $this->request->post['confirm_zeros'] == '1';

                    if ($zero_quantity_check['has_zeros'] && !$confirm_zeros) {
                        $json['confirm_required'] = true;
                        $json['confirm_message'] = sprintf(
                            'Warning: %d rows contain zero quantities which will remove stock. Do you want to continue?',
                            $zero_quantity_check['zero_count']
                        );
                        $json['zero_details'] = array_slice($zero_quantity_check['zero_details'], 0, 10); // Show first 10
                        $this->response->addHeader('Content-Type: application/json');
                        $this->response->setOutput(json_encode($json));
                        return;
                    }

                    $result = $this->model_catalog_product_stock->importProductStockData($csv_data);

                    if ($result['error_count'] > 0) {
                        if ($result['success_count'] > 0) {
                            $json['warning'] = sprintf(
                                'Import completed with %d successful updates and %d errors. %s',
                                $result['success_count'],
                                $result['error_count'],
                                implode('; ', array_slice($result['errors'], 0, 5))
                            );
                        } else {
                            $json['error'] = sprintf(
                                'Import failed with %d errors: %s',
                                $result['error_count'],
                                implode('; ', array_slice($result['errors'], 0, 5))
                            );
                        }
                    } else {
                        $json['success'] = sprintf('Successfully imported %d stock records.', $result['success_count']);
                    }
                }

            } catch (Exception $e) {
                $json['error'] = 'An error occurred during processing: ' . $e->getMessage();
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }

        // GET = Show import form
        $data['heading_title'] = $this->language->get('heading_title_import') ?: 'Import Product Stock';
        $data['text_import_description'] = $this->language->get('text_import_description') ?: 'Import product stock data from CSV file. Required columns: Product ID, Size, Color, Size-Color Quantity';
        $data['entry_import_file'] = $this->language->get('entry_import_file') ?: 'Import File';
        $data['button_import'] = $this->language->get('button_import') ?: 'Import';
        $data['button_validate'] = $this->language->get('button_validate') ?: 'Validate Only';
        $data['button_cancel'] = $this->language->get('button_cancel') ?: 'Cancel';
        $data['text_home'] = $this->language->get('text_home') ?: 'Home';

        // Breadcrumbs
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_import') ?: 'Import Product Stock',
            'href' => $this->url->link('catalog/product_stock/import', 'user_token=' . $this->session->data['user_token'], true)
        );

        // Actions
        $data['action'] = $this->url->link('catalog/product_stock/import', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('catalog/product_stock', 'user_token=' . $this->session->data['user_token'], true);

        // Add token
        $data['user_token'] = $this->session->data['user_token'];

        // Standard layout
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/product_stock_import', $data));
    }

    private function validateCSVStructure($csv_data)
    {
        $result = array('valid' => false, 'error' => '');

        if (count($csv_data) < 2) {
            $result['error'] = 'CSV file must contain at least a header row and one data row.';
            return $result;
        }

        // Check required headers (case-insensitive)
        $required_headers = array('product id', 'size', 'color', 'size-color quantity');
        
        // Remove BOM from headers and normalize
        $headers = $csv_data[0];
        $actual_headers = array_map(function($header) {
            // Remove BOM and clean header
            $header = str_replace("\xEF\xBB\xBF", '', $header);
            $header = trim($header, "\"\' \t\n\r\0\x0B");
            return strtolower($header);
        }, $headers);
        
        $header_positions = array();
        foreach ($required_headers as $required_header) {
            $position = array_search($required_header, $actual_headers);
            if ($position === false) {
                $result['error'] = 'CSV file is missing required header: ' . ucwords($required_header);
                return $result;
            }
            $header_positions[$required_header] = $position;
        }

        // Validate data rows
        for ($i = 1; $i < count($csv_data); $i++) {
            $row = $csv_data[$i];
            $row_num = $i + 1;

            // Check if row has enough columns
            if (count($row) < count($actual_headers)) {
                $result['error'] = "Row {$row_num}: Insufficient columns in data row.";
                return $result;
            }

            // Validate Product ID
            $product_id = trim($row[$header_positions['product id']]);
            if (empty($product_id) || !is_numeric($product_id) || $product_id <= 0) {
                $result['error'] = "Row {$row_num}: Product ID must be a positive number.";
                return $result;
            }

            // Validate Size
            $size = trim($row[$header_positions['size']]);
            if (empty($size)) {
                $result['error'] = "Row {$row_num}: Size cannot be empty.";
                return $result;
            }

            // Validate Color
            $color = trim($row[$header_positions['color']]);
            if (empty($color)) {
                $result['error'] = "Row {$row_num}: Color cannot be empty.";
                return $result;
            }

            // Validate Size-Color Quantity
            $quantity_raw = $row[$header_positions['size-color quantity']];
            $quantity = trim($quantity_raw);

            // Allow empty to skip DB update
            if ($quantity === '') {
                continue;
            }

            // But if present, it must be numeric and non-negative
            if (!is_numeric($quantity) || floatval($quantity) < 0) {
                $result['error'] = "Row {$row_num}: Size-Color Quantity must be a non-negative number or left empty.";
                return $result;
            }
        }

        $result['valid'] = true;
        return $result;
    }

    private function checkZeroQuantities($csv_data)
    {
        $result = array(
            'has_zeros' => false,
            'zero_count' => 0,
            'zero_details' => array()
        );

        // Remove BOM from headers and normalize
        $headers = array_map(function($header) {
            $header = str_replace("\xEF\xBB\xBF", '', $header);
            $header = trim($header, "\"\' \t\n\r\0\x0B");
            return strtolower($header);
        }, $csv_data[0]);
        $quantity_pos = array_search('size-color quantity', $headers);
        $product_id_pos = array_search('product id', $headers);
        $size_pos = array_search('size', $headers);
        $color_pos = array_search('color', $headers);

        for ($i = 1; $i < count($csv_data); $i++) {
            $row = $csv_data[$i];
            $quantity = (float)trim($row[$quantity_pos]);

            if ($quantity == 0) {
                $result['has_zeros'] = true;
                $result['zero_count']++;
                $result['zero_details'][] = sprintf(
                    'Row %d: Product ID %s, Size %s, Color %s will be set to 0',
                    $i + 1,
                    trim($row[$product_id_pos]),
                    trim($row[$size_pos]),
                    trim($row[$color_pos])
                );
            }
        }

        return $result;
    }
}
