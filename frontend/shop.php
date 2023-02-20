<?php
    require_once '../common/configs/connect.php';
    require '../common/configs/constant.php';
    require './funcs/shop_funcs.php';

    session_start();

    // Check isset cookie viewed_products
    if (array_key_exists('viewed_products', $_COOKIE)) {
        $cookie = !empty($_COOKIE['viewed_products']) ? trim($_COOKIE['viewed_products']) : '';

        $cookieId = unserialize($cookie);

        $viewedProducts = implode(', ', $cookieId);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // Data search
        $search = !empty($_GET['search']) ? trim($_GET['search']) : '';

        // Data category
        $categoryName = !empty($_GET['category']) ? trim($_GET['category']) : '';

        // Data top sellers
        $topSellers = !empty($_GET['top-sellers']) ? trim($_GET['top-sellers']) : '';
        $isBestSell = 1;

        // Data tags
        $tags = !empty($_GET['tags']) ? trim($_GET['tags']) : '';

        // Data top new
        $isNew = 1;
        $topNew = !empty($_GET['top-new']) ? trim($_GET['top-new']) : '';

        // Data recently viewed
        $recentlyViewed = !empty($_GET['recently-viewed']) ? trim($_GET['recently-viewed']) : '';

        // Pagination all product
        $listProducts = getAllProducts($connection, $active);

        $perPage = 8;

        $allNum = count($listProducts);
        $maxPage = ceil($allNum / $perPage);

        if (!empty($_GET['page'])) {
            $page = trim($_GET['page']);
        } else {
            $page = 1;
            if ($page < 1 || $page > $maxPage) {
                $page = 1;
            }
        }

        $offset = ($page - 1) * $perPage;

        // list Products
        if (!empty($search)) {
            $listAllProducts = searchProducts($connection, $search, $active, $offset, $perPage);
        } elseif (!empty($categoryName)) {
            $listAllProducts = getAllProductByCategory($connection, $categoryName, $active, $offset, $perPage);
        } elseif (!empty($topSellers)) {
            $listAllProducts = getSellProduct($connection, $isBestSell, $active, $offset, $perPage);
        } elseif (!empty($topNew)) {
            $listAllProducts = getNewProduct($connection, $isNew, $active, $offset, $perPage);
        } elseif (!empty($tags)) {
            $listAllProducts = tagProducts($connection, $tags, $active, $offset, $perPage);
        } elseif (!empty($recentlyViewed)) {
            if (!empty($cookieId) && count($cookieId) > 0) {
                $listAllProducts = getAllProductsViewed($connection, $viewedProducts, $offset, $perPage);
            }
        } else {
            $listAllProducts = getAllProductsPagination($connection, $offset, $perPage);
        }
    }
?>

<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">

<?php $pageTitle = 'Shop'; ?>
<?php require './common/header_lib.php'; ?>

<body>
    <?php require './common/navigation.php'; ?>

    <?php require './common/branding.php'; ?>

    <?php require './common/menu.php'; ?>

    <?php require './common/slider.php'; ?>


    <div class="single-product-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <h2 class="section-title">
                        <!-- Title All Products -->
                        <?php
                        if (!empty($search)) {
                            echo 'Product';
                        } elseif (!empty($categoryName)) {
                            echo 'Category ' . $categoryName;
                        } elseif (!empty($topSellers)) {
                            echo 'Top sellers';
                        } elseif (!empty($topNew)) {
                            echo 'Top New';
                        } elseif (!empty($tags)) {
                            echo 'Tags ' . $tags;
                        } elseif (!empty($recentlyViewed)) {
                            echo 'Recently viewed';
                        } else {
                            echo 'All products';
                        }
                        ?>
                    </h2>
                    <?php
                    if (!empty($listAllProducts) && count($listAllProducts) > 0) {
                        foreach ($listAllProducts as $listNumber => $listProduct) { ?>
                            <div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="single-shop-product">
                                        <div class="product-upper">
                                            <a href="./single_product.php?product-id=<?php echo $listProduct['id']; ?>">
                                                <img style="height: 260px;" src="../common/uploads/products/<?php echo $listProduct['image']; ?>" alt="">
                                            </a>
                                        </div>
                                        <h2><a href="./single_product.php?product-id=<?php echo $listProduct['id']; ?>"><?php echo limitWord($listProduct['name']); ?></a></h2>
                                        <div class="product-carousel-price">
                                            <?php if ($listProduct['old_price'] == '' || $listProduct['old_price'] == '0') { ?>
                                                <ins>$<?php echo number_format((float)$listProduct['price'], 2, '.', ''); ?></ins>
                                            <?php } else { ?>
                                                <ins>$<?php echo number_format((float)$listProduct['price'], 2, '.', ''); ?></ins> <del>$<?php echo number_format((float)$listProduct['old_price'], 2, '.', ''); ?></del>
                                            <?php } ?>
                                        </div>

                                        <div class="product-option-shop">
                                            <a class="add_to_cart_button" data-quantity="1" data-product_sku="" data-product_id="70" rel="nofollow" href="action_cart.php?action=add&product-id=<?php echo $listProduct['id']; ?>">Add to cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    }
                    ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="product-pagination text-center">
                                <nav>
                                    <ul class="pagination">
                                        <?php
                                        if ($page > 1) {
                                            $prevPage = $page - 1;

                                            echo '<li class="page-item">
                                    <a href="shop.php?page=' . $prevPage . '" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                            </li>';
                                        }

                                        for ($index = 1; $index <= $maxPage; $index++) {
                                            if ($maxPage == 1 && $page = 1) {
                                                echo '';
                                            } else { ?>
                                                <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>"><a href="<?php echo 'shop.php?page=' . $index; ?>">
                                                        <?php echo $index; ?>
                                                    </a></li>
                                        <?php }
                                        }

                                        $nextPage = $page + 1;
                                        if ($page < $maxPage) {
                                            echo '<li class="page-item">
                                    <a href="shop.php?page=' . $nextPage . '" aria-label="Previous">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                            </li>';
                                        }
                                        ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <?php require './common/footer_top.php'; ?>

    <?php require './common/footer_bottom.php'; ?>

    <?php require './common/footer_lib.php'; ?>
</body>

</html>