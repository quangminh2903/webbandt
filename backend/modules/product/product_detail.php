<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/product_funcs.php';

session_start();

if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: brand_list.php');
    exit();
}

$id = !empty($_GET['id']) ? trim($_GET['id']) : '';

// get info product by product's id
$infoProduct = getInfoProduct($connection, $id);

if (empty($infoProduct)) {
    header('location: product_list.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Detail Produc ' . $infoProduct['name'];

require '../../common/header_lib.php';
?>
<link href="../../theme/css/jquery.tagit.css" rel="stylesheet" type="text/css">
<link href="../../theme/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>

<!-- The real deal -->
<script src="../../theme/js/tag-it.js" type="text/javascript" charset="utf-8"></script>

<script>
    $(function() {
        var sampleTags = [];

        $('#readOnlyTags').tagit({
            readOnly: true
        });
    });
</script>

<body>
    <?php require '../../common/navbar.php'; ?>

    <?php require '../../common/menu.php'; ?>

    <div class="main">
        <div class="main-inner">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="widget widget-table action-table">
                            <div class="widget-header">
                                <i class="icon-book"></i>
                                <h3>Detail Product's name <b style="color:#F30">
                                        <?php echo $infoProduct['name']; ?></b> </h3>
                            </div>
                            <!-- /widget-header -->
                            <br>
                            <button class="btn btn-primary">
                                <a class="a-button" href="./product_list.php">
                                    <i class="icon-arrow-left"></i>
                                    &ensp;&nbsp;Back to list products
                                </a>
                            </button>
                            &ensp;
                            <button class="btn btn-warning">
                                <a title="Edit product" class="a-button" href="./product_edit.php?product_id=<?php $infoProduct['id']; ?>">
                                    <i class="icon-edit"></i>
                                </a>
                            </button>
                            <br><br>
                            <div class="widget-content">
                                <form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <fieldset>
                                        <br>
                                        <div class="control-group">
                                            <label class="control-label" for="name">Name</label>
                                            <div class="controls" style="margin-top:5px">
                                                <?php echo $infoProduct['name']; ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <?php if (empty($infoProduct['image'])) { ?>

                                        <?php } else { ?>
                                            <div class="control-group">
                                                <label class="control-label" for="avatar">Avatar</label>
                                                <div class="controls">
                                                    <img style="width: 150px;" src="../../../common/uploads/products/<?php echo $infoProduct['image']; ?>">
                                                </div> <!-- /controls -->
                                            </div> <!-- /control-group -->
                                        <?php } ?>

                                        <div class="control-group">
                                            <label class="control-label" for="active">Category</label>
                                            <div class="controls" style="margin-top:5px">
                                                <?php echo $infoProduct['category_name']; ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Brand</label>
                                            <div class="controls" style="margin-top:5px">
                                                <?php echo $infoProduct['brand_name']; ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Best Sell</label>
                                            <div class="controls" style="margin-top:5px">
                                                <?php
                                                if ($infoProduct['is_best_sell']  == '1') {
                                                    $infoProduct['is_best_sell'] = 'Yes';
                                                } else {
                                                    $infoProduct['is_best_sell'] = 'No';
                                                }
                                                ?>
                                                <?php echo $infoProduct['is_best_sell']; ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">New</label>
                                            <div class="controls" style="margin-top:5px">
                                                <?php
                                                if ($infoProduct['is_new']  == '1') {
                                                    $infoProduct['is_new'] = 'Yes';
                                                } else {
                                                    $infoProduct['is_new'] = 'No';
                                                }
                                                ?>
                                                <?php echo $infoProduct['is_new']; ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Active</label>
                                            <div class="controls" style="margin-top:5px">
                                                <?php
                                                if ($infoProduct['active']  == '1') {
                                                    $infoProduct['active'] = 'Yes';
                                                } else {
                                                    $infoProduct['active'] = 'No';
                                                }
                                                ?>
                                                <?php echo $infoProduct['active']; ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="price">Price</label>
                                            <div class="controls" style="margin-top:5px">
                                                <b>$<?php echo number_format($infoProduct['price']); ?></b>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <?php if (empty($infoProduct['old_price'])) { ?>

                                        <?php } else { ?>
                                            <div class="control-group">
                                                <label class="control-label" for="price">Old Price</label>
                                                <div class="controls" style="margin-top:5px">
                                                    <b><?php echo number_format($infoProduct['old_price']); ?></b>
                                                </div> <!-- /controls -->
                                            </div> <!-- /control-group -->
                                        <?php } ?>

                                        <div class="control-group">
                                            <label class="control-label" for="tag">Tags</label>
                                            <div class="controls">
                                                <div class="span-4" style="margin-left: 0; margin-top:5px">
                                                    <?php echo $infoProduct['tags']; ?>
                                                </div>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="sort_order">Sort Order</label>
                                            <div class="controls" style="margin-top:5px">
                                                <?php echo $infoProduct['sort_order']; ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="row">
                                            <div class="span8">
                                                <div class="control-group">
                                                    <label class="control-label" for="description">Description</label>
                                                    <div class="controls" style="margin-top:5px">
                                                        <?php echo $infoProduct['description']; ?>
                                                    </div> <!-- /controls -->
                                                </div> <!-- /control-group -->
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>

                            </div>
                            <!-- /widget-content -->
                        </div>
                    </div>
                    <!-- /span6 -->
                </div>
                <!-- /row -->

            </div>
            <!-- /container -->
        </div>
        <!-- /main-inner -->
    </div>
    <!-- /main -->

    <?php require '../../common/footer_top.php'; ?>

    <?php require '../../common/footer_bottom.php'; ?>
</body>

</html>