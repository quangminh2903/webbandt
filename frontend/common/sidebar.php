<div class="single-sidebar">
    <h2 class="sidebar-title">Search Products</h2>
    <form action="./shop.php">
        <input type="text" name="search" placeholder="Search products...">
        <input type="submit" value="Search">
    </form>
</div>

<div class="single-sidebar">

    <?php
    // Get all products limit 4 product
    $listProductsLimit = getAllProducts($connection, $active);

    if (count($listProductsLimit) > 0) {
        echo '<h2 class="sidebar-title">Products</h2>';

        foreach ($listProductsLimit as $product) { ?>
            <div class="thubmnail-recent">
                <img src="../common/uploads/products/<?php echo $product['image']; ?>" class="recent-thumb" alt="">

                <h2>
                    <a href="single_product.php?product-id=<?php echo $product['id']; ?>"><?php echo limitWord($product['name']);; ?>
                    </a>
                </h2>

                <div class="product-sidebar-price">
                    <?php if ($product['old_price'] == '' || $product['old_price'] == '0') { ?>
                        <ins>
                            $<?php echo number_format((float)$product['price'], 2, '.', ''); ?>
                        </ins>

                    <?php } else { ?>
                        <ins>
                            $<?php echo number_format((float)$product['price'], 2, '.', ''); ?>
                        </ins>

                        <del>
                            $<?php echo number_format((float)$product['old_price'], 2, '.', ''); ?>
                        </del>

                    <?php } ?>
                </div>
            </div>
    <?php }
    }
    ?>

</div>

<div class="single-sidebar">
    <?php
    if (!empty($cookieId) && count($cookieId) > 0) {
        echo '<h2 class="sidebar-title">Recent Posts</h2>';

        foreach (array_slice($cookieId, 0, 3) as $id) {
            $listProductsViewedLimit = getAllProductsViewed($connection, $id, $active);

            if (count($listProductsViewedLimit) > 0) {
                foreach ($listProductsViewedLimit as $product) { ?>
                    <div class="thubmnail-recent">
                        <img src="../common/uploads/products/<?php echo $product['image']; ?>" class="recent-thumb" alt="">

                        <h2>
                            <a href="single_product.php?product-id=<?php echo $product['id']; ?>">
                                <?php echo limitWord($product['name']); ?>
                            </a>
                        </h2>

                        <div class="product-sidebar-price">
                            <?php if ($product['old_price'] == '' || $product['old_price'] == '0') { ?>
                                <ins>
                                    $<?php echo number_format((float)$product['price'], 2, '.', ''); ?>
                                </ins>

                            <?php } else { ?>
                                <ins>
                                    $<?php echo number_format((float)$product['price'], 2, '.', ''); ?>
                                </ins>

                                <del>
                                    $<?php echo number_format((float)$product['old_price'], 2, '.', ''); ?>
                                </del>

                            <?php } ?>
                        </div>
                    </div>
    <?php }
            }
        }
    }
    ?>
</div>