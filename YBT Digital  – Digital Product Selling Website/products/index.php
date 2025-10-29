<?php
require_once __DIR__ . '/../config/config.php';

$pageTitle = 'Products - ' . SITE_NAME;

// Get filters
$categoryId = isset($_GET['category']) ? intval($_GET['category']) : 0;
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$sortBy = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'latest';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Build query
$where = ["p.status = 'active'"];
$params = [];
$types = '';

if ($categoryId > 0) {
    $where[] = "p.category_id = ?";
    $params[] = $categoryId;
    $types .= 'i';
}

if (!empty($search)) {
    $where[] = "(p.title LIKE ? OR p.description LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ss';
}

$whereClause = implode(' AND ', $where);

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM products p WHERE $whereClause";
$countResult = fetchOne($countQuery, $params, $types);
$totalProducts = $countResult['total'];

// Pagination
$pagination = paginate($totalProducts, $page, PRODUCTS_PER_PAGE);

// Sorting
$orderBy = match($sortBy) {
    'price_low' => 'p.price ASC',
    'price_high' => 'p.price DESC',
    'popular' => 'p.sales DESC',
    'name' => 'p.title ASC',
    default => 'p.created_at DESC'
};

// Get products
$productsQuery = "SELECT p.*, c.name as category_name 
                  FROM products p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  WHERE $whereClause 
                  ORDER BY $orderBy 
                  LIMIT ? OFFSET ?";
$params[] = PRODUCTS_PER_PAGE;
$params[] = $pagination['offset'];
$types .= 'ii';

$products = fetchAll($productsQuery, $params, $types);

// Get categories for filter
$categoriesQuery = "SELECT * FROM categories WHERE status = 'active' ORDER BY name";
$categories = fetchAll($categoriesQuery);

include __DIR__ . '/../includes/header.php';
?>

<style>
    .filters-section {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }
    }
    
    .product-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    .product-image {
        height: 200px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: rgba(0,0,0,0.1);
        position: relative;
    }
    
    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--danger-color);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: bold;
    }
    
    .pagination {
        margin-top: 2rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-icon {
        font-size: 5rem;
        color: var(--text-secondary);
        opacity: 0.3;
        margin-bottom: 1rem;
    }
</style>

<div class="container my-5">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="fw-bold mb-2">
            <?php echo $categoryId ? 'Category: ' . sanitize($categories[array_search($categoryId, array_column($categories, 'id'))]['name'] ?? 'Products') : 'All Products'; ?>
        </h1>
        <p class="text-muted">
            <?php echo $totalProducts; ?> product<?php echo $totalProducts != 1 ? 's' : ''; ?> found
        </p>
    </div>
    
    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search products..." 
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
            
            <div class="col-md-3">
                <label class="form-label fw-bold">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $categoryId == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo sanitize($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label fw-bold">Sort By</label>
                <select name="sort" class="form-select">
                    <option value="latest" <?php echo $sortBy === 'latest' ? 'selected' : ''; ?>>Latest</option>
                    <option value="popular" <?php echo $sortBy === 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                    <option value="price_low" <?php echo $sortBy === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_high" <?php echo $sortBy === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                    <option value="name" <?php echo $sortBy === 'name' ? 'selected' : ''; ?>>Name</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Products Grid -->
    <?php if (!empty($products)): ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="card product-card">
                    <div class="product-image">
                        <?php if ($product['discount_price']): ?>
                            <?php 
                            $discount = round((($product['price'] - $product['discount_price']) / $product['price']) * 100);
                            ?>
                            <span class="product-badge"><?php echo $discount; ?>% OFF</span>
                        <?php endif; ?>
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <?php if ($product['category_name']): ?>
                            <span class="badge bg-primary mb-2 align-self-start"><?php echo sanitize($product['category_name']); ?></span>
                        <?php endif; ?>
                        
                        <h5 class="card-title fw-bold mb-2">
                            <a href="<?php echo SITE_URL; ?>/product.php?id=<?php echo $product['id']; ?>" 
                               class="text-decoration-none text-dark">
                                <?php echo sanitize($product['title']); ?>
                            </a>
                        </h5>
                        
                        <p class="card-text text-muted small mb-3 flex-grow-1">
                            <?php echo substr(sanitize($product['description']), 0, 80) . '...'; ?>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div>
                                <?php if ($product['discount_price']): ?>
                                    <div class="price-tag"><?php echo formatPrice($product['discount_price']); ?></div>
                                    <small class="old-price"><?php echo formatPrice($product['price']); ?></small>
                                <?php else: ?>
                                    <div class="price-tag"><?php echo formatPrice($product['price']); ?></div>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo SITE_URL; ?>/product.php?id=<?php echo $product['id']; ?>" 
                               class="btn btn-primary btn-sm">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
            <nav aria-label="Products pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&category=<?php echo $categoryId; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sortBy; ?>">
                                Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <?php if ($i == 1 || $i == $pagination['total_pages'] || abs($i - $page) <= 2): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&category=<?php echo $categoryId; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sortBy; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php elseif (abs($i - $page) == 3): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $pagination['total_pages']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&category=<?php echo $categoryId; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sortBy; ?>">
                                Next
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 class="fw-bold mb-3">No Products Found</h3>
            <p class="text-muted mb-4">Try adjusting your filters or search terms</p>
            <a href="<?php echo SITE_URL; ?>/products" class="btn btn-primary">
                View All Products
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
