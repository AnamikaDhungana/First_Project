<?php
if (isset($_GET['category'])) {
    $categoryName = $_GET['category']; // Get category from URL
} else {
    $categoryName = ''; // Default if no category
}

include('../database_connection.php'); // DB connection

// Get search query
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

if (!empty($searchQuery)) {
    // Sanitize input
    $searchQuery = mysqli_real_escape_string($conn, $searchQuery);
    
    // Convert to lowercase for case-insensitive search
    $searchQueryLower = strtolower($searchQuery);

    // Determine if searching for tea or accessories
    $isTeaSearch = stripos($searchQueryLower, 'tea') !== false;
    $isAccessorySearch = stripos($searchQueryLower, 'accessory') !== false || stripos($searchQueryLower, 'accessories') !== false;

    // Base SQL Query with LOWER() for case-insensitive matching
    $sql = "SELECT product_name, product_price, product_description, product_name_np, product_price_np, product_description_np, image_url, category
            FROM products 
            WHERE (LOWER(product_name) LIKE ? 
            OR LOWER(product_description) LIKE ? 
            OR LOWER(product_name_np) LIKE ? 
            OR LOWER(product_description_np) LIKE ? 
            OR LOWER(category) LIKE ? )";

    // Adjust query based on category filter
    if ($isTeaSearch) {
        $sql .= " AND LOWER(category) IN ('black tea', 'flower tea', 'herbal tea', 'green tea')";
    } elseif ($isAccessorySearch) {
        $sql .= " AND LOWER(category) = 'accessories'";
    }

    // Prepare Statement
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        echo json_encode(["error" => "Error preparing statement: " . mysqli_error($conn)]);
        exit;
    }

    // Add wildcard for LIKE search
    $searchParam = "%" . $searchQueryLower . "%";
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, 'sssss', $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);

    // Execute the Query
    if (!mysqli_stmt_execute($stmt)) {
        echo json_encode(["error" => "Error executing statement: " . mysqli_error($conn)]);
        exit;
    }

    // Get Results
    $result = mysqli_stmt_get_result($stmt);
    $searchResults = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $searchResults[] = $row;
    }

    // Display Results as JSON
    header('Content-Type: application/json');
    echo json_encode($searchResults);
    exit;

    // Close Statement and Connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo json_encode(["message" => "Please enter a search query."]);
}
?>
