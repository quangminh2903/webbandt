<?php $currentUrl = getCurrentUrl(); ?>

<?php if ($currentUrl == 'index.php') { ?>
    <div class="slider-area">
        <!-- Slider -->
        <div class="block-slider block-slider4">
            <ul class="" id="bxslider-home4">
                <?php
                $active = 1;
                $listBanners = getAllBanners($connection, $active);

                if (count($listBanners) > 0) {
                    foreach ($listBanners as $banner) {
                        $arrTitle = explode(" ", $banner['title'], 3)
                ?>
                        <li>
                            <img src="../common/uploads/banners/<?php echo $banner['image_url']; ?>" alt="Slide" />
                            <div class="caption-group">

                                <!-- <h2 class="caption title">
                                    <?php echo $arrTitle[0]; ?>
                                    <span class="primary">
                                        <?php echo !empty($arrTitle[1]) ? $arrTitle[1] : ''; ?>
                                        <strong><?php echo !empty($arrTitle[2]) ? $arrTitle[2] : ''; ?></strong>
                                    </span>
                                </h2>

                                <h4 class="caption subtitle">
                                    <?php echo $banner['content']; ?>
                                </h4> -->

                                <a class="caption button-radius" href="shop.php">
                                    <span class="icon"></span>
                                    Shop now
                                </a>

                            </div>
                        </li>
                <?php
                    }
                }
                ?>
            </ul>
        </div>
        <!-- ./Slider -->
    </div>
    <!-- End slider area -->
<?php } else { ?>
    <?php
    $pageTitle = 'Shop';

    if ($currentUrl == 'cart.php' || $currentUrl == 'checkout.php') {
        $pageTitle = 'Shopping Cart';
    }
    ?>

    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h2><?php echo $pageTitle; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End Page title area -->
<?php } ?>