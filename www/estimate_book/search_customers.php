<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('lib/mydb.php'));

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = db_connect();

    // Handle JSON input if $_POST is empty
    if (empty($_POST)) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (is_array($data)) {
            $_POST = $data;
        }
    }

    // Debug logging
    file_put_contents('debug_search.log', print_r($_POST, true), FILE_APPEND);

    // Tabulator parameters
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $size = isset($_POST['size']) ? (int)$_POST['size'] : 50;
    
    // Tabulator sends 'filter' by default, but let's check both
    $filters = isset($_POST['filter']) ? $_POST['filter'] : (isset($_POST['filters']) ? $_POST['filters'] : []);
    $sorters = isset($_POST['sorters']) ? $_POST['sorters'] : [];

    // Search filter
    $search = '';
    if (isset($_POST['search'])) {
        // If sent as a simple param
        $search = $_POST['search'];
    } else if (!empty($filters)) {
        // If sent via Tabulator filter array
        foreach ($filters as $filter) {
            if ($filter['field'] == 'search') {
                $search = $filter['value'];
            }
        }
    }

    $offset = ($page - 1) * $size;

    $where = "WHERE is_deleted = 'N'";
    $params = [];

    if (!empty($search)) {
        $where .= " AND (display_name LIKE ? OR company_name LIKE ? OR mobile_phone LIKE ? OR email LIKE ? OR memo LIKE ?)";
        $term = "%$search%";
        $params = array_merge($params, [$term, $term, $term, $term, $term]);
    }

    // Count total
    $countSql = "SELECT COUNT(*) FROM estimate_customer $where";
    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $total = $stmt->fetchColumn();

    // Fetch data
    $sql = "SELECT num as id, display_name, company_name, department, work_phone, home_phone, mobile_phone, email, memo 
            FROM estimate_customer 
            $where 
            ORDER BY num DESC 
            LIMIT $size OFFSET $offset";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Tabulator expects 'last_page' for remote pagination
    $last_page = ceil($total / $size);

    echo json_encode([
        'last_page' => $last_page,
        'data' => $data,
        'total' => $total
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>
