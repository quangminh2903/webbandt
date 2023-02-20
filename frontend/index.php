<?php
require_once '../common/configs/connect.php';
require '../common/configs/constant.php';
require './funcs/index_funcs.php';

session_start();

// Get list id reviewed recently
if (array_key_exists('viewed_products', $_COOKIE)) {
    $cookie = $_COOKIE['viewed_products'];
    $cookieId = unserialize($cookie);
}
?>

<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">

<?php $pageTitle = 'Ustora Demo'; ?>

<?php require './common/header_lib.php'; ?>

<body>
    <?php require './common/navigation.php'; ?>

    <?php require './common/branding.php'; ?>

    <?php require './common/menu.php'; ?>

    <?php require './common/slider.php'; ?>

    <div class="promo-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="single-promo promo1">
                        <i class="fa fa-refresh"></i>
                        <p>30 Days return</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-promo promo2">
                        <i class="fa fa-truck"></i>
                        <p>Free shipping</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-promo promo3">
                        <i class="fa fa-lock"></i>
                        <p>Secure payments</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-promo promo4">
                        <i class="fa fa-gift"></i>
                        <p>New products</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End promo area -->

    <div class="maincontent-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="latest-product">
                        <?php
                        $listNewProducts = getAllNewProduct($connection, $active);
                        if (count($listNewProducts) > 0) {
                        ?>
                            <h2 class="section-title">Latest Products</h2>

                            <div class="product-carousel">

                                <?php
                                foreach ($listNewProducts as $product) {  ?>
                                    <div class="single-product">
                                        <div class="product-f-image">
                                            <img src="../common/uploads/products/<?php echo $product['image']; ?>" alt="" />
                                            <div class="product-hover">
                                                <a href="action_cart.php?action=add&product-id=<?php echo $product['id']; ?>" class="add-to-cart-link"><i class="fa fa-shopping-cart"></i> Add to cart</a>
                                                <a href="single_product.php?product-id=<?php echo $product['id']; ?>" class="view-details-link"><i class="fa fa-link"></i> See details</a>
                                            </div>
                                        </div>

                                        <h2>
                                            <a href="single_product.php?product-id=<?php echo $product['id']; ?>"><?php echo limitWord($product['name']); ?></a>
                                        </h2>

                                        <div class="product-carousel-price">
                                            <?php if ($product['old_price'] == '' || $product['old_price'] == '0') { ?>
                                                <ins>$<?php echo number_format((float)$product['price'], 2, '.', ''); ?></ins>
                                            <?php } else { ?>
                                                <ins>$<?php echo number_format((float)$product['price'], 2, '.', ''); ?></ins> <del>$<?php echo number_format((float)$product['old_price'], 2, '.', ''); ?></del>
                                            <?php } ?>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End main content area -->

    <div class="brands-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="brand-wrapper">
                        <div class="brand-list">
                            <?php
                            $listBrands = getAllBrands($connection, $active);

                            if (count($listBrands) > 0) {
                                foreach ($listBrands as $brand) {
                                    echo '<a href="' . $brand['link'] . '" target="_blank"><img  src="../common/uploads/brands/' . $brand['image_url'] . '" alt="" /></a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End brands area -->

    <div class="product-widget-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="single-product-widget">

                        <?php
                        $isBestSell = 1;

                        $listSellProducts = getSellProduct($connection, $isBestSell, $active);

                        if (count($listSellProducts) > 0) {
                        ?>

                            <h2 class="product-wid-title">Top Sellers</h2>
                            <a href="./shop.php?top-sellers=top-sellers" class="wid-view-more">View All</a>

                            <?php
                            foreach ($listSellProducts as $product) {  ?>
                                <div class="single-wid-product">
                                    <a href="single_product.php?product-id=<?php echo $product['id']; ?>">
                                        <img src="../common/uploads/products/<?php echo $product['image']; ?>" alt="" class="product-thumb" />
                                    </a>

                                    <h2>
                                        <a href="single_product.php?product-id=<?php echo $product['id']; ?>"><?php echo limitWord($product['name']); ?></a>
                                    </h2>

                                    <div class="product-wid-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>

                                    <div class="product-wid-price">
                                        <?php if ($product['old_price'] == '' || $product['old_price'] == '0') { ?>
                                            <ins>$<?php echo number_format((float)$product['price'], 2, '.', ''); ?></ins>
                                        <?php } else { ?>
                                            <ins>$<?php echo number_format((float)$product['price'], 2, '.', ''); ?></ins> <del>$<?php echo number_format((float)$product['old_price'], 2, '.', ''); ?></del>
                                        <?php } ?>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="single-product-widget">

                        <?php
                        if (!empty($cookieId) && count($cookieId) > 0) {
                        ?>
                            <h2 class="product-wid-title">Recently Viewed</h2>
                            <a href="shop.php?recently-viewed=recently-viewed" class="wid-view-more">View All</a>
                            <?php
                            foreach (array_slice($cookieId, 0, 3) as $id) {
                                $listViewedProducts = getAllProductsViewd($connection, $id, $active);

                                if (count($listViewedProducts) > 0) {
                                    foreach ($listViewedProducts as $product) {  ?>
                                        <div class="single-wid-product">
                                            <a href="single_product.php?product-id=<?php echo $product['id']; ?>"><img src="../common/uploads/products/<?php echo $product['image']; ?>" alt="" class="product-thumb" /></a>
                                            <h2><a href="single_product.php?product-id=<?php echo $product['id']; ?>"><?php echo limitWord($product['name']); ?></a></h2>
                                            <div class="product-wid-rating">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <div class="product-wid-price">
                                                <?php if ($product['old_price'] == '' || $product['old_price'] == '0') { ?>
                                                    <ins>$<?php echo number_format((float)$product['price'], 2, '.', ''); ?></ins>
                                                <?php } else { ?>
                                                    <ins>$<?php echo number_format((float)$product['price'], 2, '.', ''); ?></ins> <del>$<?php echo number_format((float)$product['old_price'], 2, '.', ''); ?></del>
                                                <?php } ?>
                                            </div>
                                        </div>
                        <?php
                                    }
                                }
                            }
                        }
                        ?>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="single-product-widget">

                        <?php
                        $isNew = 1;
                        $listNewProduct = getNewProduct($connection, $isNew, $active);
                        if (count($listNewProducts) > 0) {
                        ?>

                            <h2 class="product-wid-title">Top New</h2>
                            <a href="./shop.php?top-new=top-new" class="wid-view-more">View All</a>

                            <?php
                            foreach ($listNewProduct as $product) {  ?>
                                <div class="single-wid-product">
                                    <a href="single_product.php?product-id=<?php echo $product['id']; ?>"><img src="../common/uploads/products/<?php echo $product['image']; ?>" alt="" class="product-thumb" /></a>
                                    <h2><a href="single_product.php?product-id=<?php echo $product['id']; ?>"><?php echo limitWord($product['name']); ?></a></h2>
                                    <div class="product-wid-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <div class="product-wid-price">
                                        <?php if ($product['old_price'] == '' || $product['old_price'] == '0') { ?>
                                            <ins>$<?php echo number_format((float)$product['price'], 2, '.', ''); ?></ins>
                                        <?php } else { ?>
                                            <ins>$<?php echo number_format((float)$product['price'], 2, '.', ''); ?></ins> <del>$<?php echo number_format((float)$product['old_price'], 2, '.', ''); ?></del>
                                        <?php } ?>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End product widget area -->

    <?php require './common/footer_top.php'; ?>

    <?php require './common/footer_bottom.php'; ?>

    <?php require './common/footer_lib.php'; ?>
</body>

</html>