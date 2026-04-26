<?php
// Solr Configuration
$solrHost = 'localhost';
$solrPort = 8983;
$coreName = 'tech_store';
$solrUrl = "http://$solrHost:$solrPort/solr/$coreName";

// Handle Search
$query = isset($_GET['q']) ? $_GET['q'] : '*:*';
$fq = isset($_GET['fq']) ? $_GET['fq'] : '';

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $newProduct = [
        'id' => 'prod_' . time(),
        'name' => $_POST['name'],
        'category' => $_POST['category'],
        'price' => (float)$_POST['price'],
        'inStock' => isset($_POST['inStock']),
        'description' => $_POST['description']
    ];
    
    $ch = curl_init("$solrUrl/update?commit=true");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([$newProduct]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
    header("Location: index.php?msg=Product+Added");
    exit;
}

// Fetch Results
$searchUrl = "$solrUrl/select?" . http_build_query([
    'q' => $query,
    'fq' => $fq,
    'facet' => 'true',
    'facet.field' => 'category',
    'hl' => 'true',
    'hl.fl' => 'description,name',
    'wt' => 'json'
]);

$response = @file_get_contents($searchUrl);
$data = $response ? json_decode($response, true) : null;
$docs = isset($data['response']['docs']) ? $data['response']['docs'] : [];
$facets = isset($data['facet_counts']['facet_fields']['category']) ? array_chunk($data['facet_counts']['facet_fields']['category'], 2) : [];
$highlighting = isset($data['highlighting']) ? $data['highlighting'] : [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Solr Tech Store</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🛒 Solr Tech Store</h1>
        <form action="index.php" method="GET" class="search-form">
            <input type="text" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="Search products...">
            <button type="submit">Search</button>
        </form>
    </header>

    <main>
        <aside>
            <h3>Categories</h3>
            <ul>
                <li><a href="index.php?q=<?php echo urlencode($query); ?>">All Categories</a></li>
                <?php foreach ($facets as $facet): ?>
                    <?php if ($facet[1] > 0): ?>
                        <li>
                            <a href="index.php?q=<?php echo urlencode($query); ?>&fq=category:<?php echo urlencode($facet[0]); ?>">
                                <?php echo htmlspecialchars($facet[0]); ?> (<?php echo $facet[1]; ?>)
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <hr>

            <h3>Add New Product</h3>
            <form action="index.php" method="POST" class="add-form">
                <input type="hidden" name="action" value="add">
                <input type="text" name="name" placeholder="Product Name" required>
                <input type="text" name="category" placeholder="Category" required>
                <input type="number" step="0.01" name="price" placeholder="Price" required>
                <label><input type="checkbox" name="inStock" checked> In Stock</label>
                <textarea name="description" placeholder="Description"></textarea>
                <button type="submit">Add Product</button>
            </form>
        </aside>

        <section class="results">
            <?php if (!$data): ?>
                <div class="error">
                    Could not connect to Solr core "<?php echo $coreName; ?>". 
                    Make sure it exists: <code>bin/solr create -c <?php echo $coreName; ?></code>
                </div>
            <?php elseif (empty($docs)): ?>
                <p>No products found.</p>
            <?php else: ?>
                <p><?php echo $data['response']['numFound']; ?> products found.</p>
                <div class="product-grid">
                    <?php foreach ($docs as $doc): ?>
                        <div class="product-card">
                            <h2>
                                <?php 
                                    echo isset($highlighting[$doc['id']]['name']) 
                                        ? implode('...', $highlighting[$doc['id']]['name']) 
                                        : htmlspecialchars($doc['name']); 
                                ?>
                            </h2>
                            <span class="price">$<?php echo number_format($doc['price'], 2); ?></span>
                            <span class="tag"><?php echo htmlspecialchars($doc['category']); ?></span>
                            <p>
                                <?php 
                                    echo isset($highlighting[$doc['id']]['description']) 
                                        ? '...' . implode('...', $highlighting[$doc['id']]['description']) . '...' 
                                        : htmlspecialchars($doc['description']); 
                                ?>
                            </p>
                            <div class="stock <?php echo $doc['inStock'] ? 'in-stock' : 'out-of-stock'; ?>">
                                <?php echo $doc['inStock'] ? '● In Stock' : '○ Out of Stock'; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
