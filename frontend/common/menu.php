<?php
    $currentUrl = getCurrentUrl();

    $authUrl = ($currentUrl == 'login.php' || $currentUrl == 'register.php' || $currentUrl == 'forgot_password.php' || $currentUrl == 'reset_password.php' || $currentUrl == 'change_password.php' || $currentUrl == 'profile.php');

    $authUrl ? require '../funcs/category_funcs.php' : require './funcs/category_funcs.php';

    $active = 1;

    $listCate = getCategories($connection, $active);
?>

<div class="mainmenu-area">
    <div class="container">
        <div class="row">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li <?php echo $currentUrl == 'index.php' ? 'class="active"' : ''; ?>><a href="<?php echo $authUrl ? '../index.php' : 'index.php' ?>">Home</a></li>

                    <li <?php echo $currentUrl == 'shop.php' ? 'class="active"' : ''; ?>><a href="<?php echo $authUrl ? '../shop.php' : 'shop.php' ?>">Products</a></li>

                    <?php
                    if (count($listCate) > 0) {
                        foreach ($listCate as $category) {
                    ?>
                            <li <?php echo $currentUrl == 'shop.php?category=' . $category['category_name'] . '' ? 'class="active"' : ''; ?>>
                                <a href="<?php echo $authUrl ? '../shop.php?category=' . $category['category_name'] . '' : 'shop.php?category=' . strtolower($category['category_name']) . '' ?>">
                                    <?php echo $category['category_name']; ?>
                                </a>
                            </li>

                    <?php }
                    }
                    ?>

                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End mainmenu area -->