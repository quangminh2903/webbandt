<?php
require '../common/configs/constant.php';
require_once '../common/configs/connect.php';
require_once './funcs/single_product_funcs.php';
require './funcs/cart_funcs.php';

session_start();

// Check product id
if (empty($_GET['product-id']) || !is_numeric($_GET['product-id'])) {
    header('location: index.php');
    exit();
}

$id = !empty($_GET['product-id']) ? trim($_GET['product-id']) : '';

// Get info product
$infoProduct = getInfoProduct($connection, $id);

if (empty($infoProduct)) {
    $_SESSION['error'] = 'Not exist product id';

    header('location: index.php');
    exit();
}

$categoryId = $infoProduct['category_id'];
$productId = $infoProduct['id'];

// Get list all products
$listAllProducts = getAllProduct($connection, $active);

if (empty($infoProduct)) {
    header('location: index.php');
    exit();
}

// Create cookie
if (array_key_exists('viewed_products', $_COOKIE)) {
    $cookie = $_COOKIE['viewed_products'];
    $cookie = unserialize($cookie);
} else {
    $cookie = array();
}

// Add product'id to cookie
$cookie[] = $productId;
$cookie = array_unique($cookie);
$cookie = serialize($cookie);

// Save the cookie
setcookie('viewed_products', $cookie, time() + (8400 * 3600));

$cookieId = unserialize($cookie);

$quantity = !empty($_POST['quantity']) ? trim($_POST['quantity']) : '';

if (isset($_POST['add-cart'])) {
    echo $quantity;
    if (isset($_COOKIE['shopping-cart'])) {
        $cookieData = stripslashes($_COOKIE['shopping-cart']);
        $cartData = json_decode($cookieData, true);
    } else {
        $cartData = [];
    }

    if (array_key_exists($productId, $cartData)) {
        $cartData[$productId]['quantity'] += $quantity;
    } else {
        $product = getProductById($connection, $active, $productId);

        $cartData[$productId] = [
            'name' => $product['name'],
            'image' => $product['image'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }

    $itemCount = count($cartData);
    setcookie('shopping-cart', json_encode($cartData), time() + (86400 * 30), '/');

    $_SESSION['success'] = 'Add ' . $quantity . ' products ' . $product['name'] . ' success';

    header('location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">

<?php $pageTitle = $infoProduct['name']; ?>
<?php require './common/header_lib.php'; ?>

<body>

    <?php require './common/navigation.php'; ?>

    <?php require './common/branding.php'; ?>

    <?php require './common/menu.php'; ?>

    <?php require './common/slider.php'; ?>

    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <?php require './common/sidebar.php'; ?>
                </div>

                <div class="col-md-8">
                    <div class="product-content-right">
                        <div class="product-breadcroumb">
                            <a href="index.php">Home</a>
                            <a href="shop.php?category=<?php echo $infoProduct['category_name']; ?>"><?php echo $infoProduct['category_name']; ?></a>
                            <a href=""><?php echo $infoProduct['name']; ?></a>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="product-images">
                                    <div class="product-main-img">
                                        <img src="../common/uploads/products/<?php echo $infoProduct['image']; ?>" alt="">
                                    </div>

                                    <div class="product-gallery">
                                        <?php
                                        $listGallery = getGallery($connection, $productId, $active);

                                        if (count($listGallery) > 0) {
                                            foreach ($listGallery as $gallery) {
                                        ?>
                                                <img src="../common/uploads/galleries/<?php echo $gallery['image_url']; ?>" alt="">
                                        <?php
                                            }
                                        }

                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="product-inner">
                                    <h2 class="product-name"><?php echo $infoProduct['name']; ?></h2>
                                    <div class="product-inner-price">
                                        <?php if ($infoProduct['old_price'] == '' || $infoProduct['old_price'] == '0') { ?>
                                            <ins>$<?php echo number_format((float)$infoProduct['price'], 2, '.', ''); ?></ins>
                                        <?php } else { ?>
                                            <ins>$<?php echo number_format((float)$infoProduct['price'], 2, '.', ''); ?></ins> <del>$<?php echo number_format((float)$infoProduct['old_price'], 2, '.', ''); ?></del>
                                        <?php } ?>
                                    </div>

                                    <form action="" method="POST" class="cart">
                                        <div class="quantity">
                                            <input type="number" size="4" class="input-text qty text" title="Qty" value="1" name="quantity" min="1" step="1">
                                        </div>

                                        <input type="submit" value="Add to cart" name="add-cart" class="button">
                                    </form>

                                    <div class="product-inner-category">
                                        <p>Category: <a href="shop.php?category=<?php echo $infoProduct['category_name']; ?>"><?php echo $infoProduct['category_name']; ?></a>.

                                            <?php $arrTags = explode(',', $infoProduct['tags']); ?>
                                            Tags: <?php foreach ($arrTags as $tag) {
                                                        $separator = ($tag != end($arrTags)) ? ', ' : '';

                                                        echo '<a href="shop.php?tags=' . strtolower($tag) . '">' . $tag . '</a>' . $separator;
                                                    } ?>
                                        </p>
                                    </div>

                                    <div role="tabpanel">
                                        <ul class="product-tab" role="tablist">
                                            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Description</a></li>
                                        </ul>
                                        <div class="tab-content">

                                            <?php if ($infoProduct['description'] != '' || $infoProduct['description'] != null) {  ?>
                                                <h2>Product Description</h2>
                                                <p><?php echo $infoProduct['description']; ?></p>
                                            <?php } ?>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <?php $listProductRelated = getProductTogether($connection, $active, $categoryId, $productId); ?>

                        <div class="related-products-wrapper">
                            <?php if (count($listProductRelated) > 0) {
                                echo '<h2 class="related-products-title">Related Products</h2>';
                            }
                            ?>

                            <div class="related-products-carousel">
                                <?php
                                if (count($listProductRelated) > 0) {
                                    foreach ($listProductRelated as $product) {
                                ?>
                                        <div class="single-product">
                                            <div class="product-f-image">
                                                <img style="height: 250px;" src="../common/uploads/products/<?php echo $product['image']; ?>" alt="">
                                                <div class="product-hover">
                                                    <a href="action_cart.php?action=add&product-id=<?php echo $product['id']; ?>" class="add-to-cart-link"><i class="fa fa-shopping-cart"></i> Add to cart</a>
                                                    <a href="./single_product.php?product-id=<?php echo $product['id']; ?>" class="view-details-link"><i class="fa fa-link"></i> See details</a>
                                                </div>
                                            </div>

                                            <h2><a href="./single_product.php?product-id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></h2>

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
    </div>


    <?php require './common/footer_top.php'; ?>

    <?php require './common/footer_bottom.php'; ?>

    <?php require './common/footer_lib.php'; ?>
</body>

</html>