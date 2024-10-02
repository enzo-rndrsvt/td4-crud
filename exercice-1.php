<?php
// Function to load JSON file and return it as an array
function getProducts($filePath) {
    $json = file_get_contents($filePath);
    $productsArray = json_decode($json, true);
    return $productsArray['products'];
}

// Function to display the products in an HTML table with optional brand filter
function displayProducts($products, $filterBrand = null) {
    echo "<table border='0'>";
    echo "<tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Thumbnail</th>
          </tr>";
    
    foreach ($products as $product) {
        if ($filterBrand && strtolower($product['brand']) != strtolower($filterBrand)) {
            continue; // Skip products that don't match the filter
        }

        echo "<tr>";
        echo "<td>" . $product['id'] . "</td>";
        echo "<td>" . $product['title'] . "</td>";
        echo "<td>" . $product['description'] . "</td>";
        echo "<td>" . $product['price'] . "</td>";
        echo "<td>" . $product['brand'] . "</td>";
        echo "<td>" . $product['category'] . "</td>";
        echo "<td><img src='" . $product['thumbnail'] . "' alt='" . $product['title'] . "' width='50'></td>";
        echo "</tr>";
    }

    echo "</table>";
}

// Get the brand filter from URL parameter
$filterBrand = isset($_GET['marque']) ? $_GET['marque'] : null;

// Load the products from the JSON file
$products = getProducts('./products.json'); // Replace with the correct path

// Display the products, applying the filter if set
displayProducts($products, $filterBrand);
?>
