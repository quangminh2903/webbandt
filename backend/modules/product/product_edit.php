<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/product_funcs.php';
require_once '../../common/validate.php';

session_start();

// Check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

// Check and validate id
if (empty($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    header('location: product_list.php');
    exit();
}
$id = !empty($_GET['product_id']) ? trim($_GET['product_id']) : '';

// Get info product by id
$infoProduct = getInfoProductEdit($connection, $id, $activeDelete);

if (empty($infoProduct)) {
    $_SESSION['error'] = 'Not exist product id';

    header('location: product_list.php');
    exit();
}

// Get category's name
$nameCategory = getNameCategory($connection, $active);

// Get brand's name
$nameBrand = getNameBrand($connection, $active);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Get form data
    $name = !empty($_POST['name']) ? trim($_POST['name']) : '';
    $categoryId = !empty($_POST['category_id']) ? trim($_POST['category_id']) : '';
    $brandId = !empty($_POST['brand_id']) ? trim($_POST['brand_id']) : '';
    $avatarFile = !empty($_FILES['avatar']) ? $_FILES['avatar'] : '';
    $price = !empty($_POST['price']) ? trim($_POST['price']) : '';
    $oldPrice = !empty($_POST['old_price']) ? trim($_POST['old_price']) : '';
    $description = !empty($_POST['description']) ? trim($_POST['description']) : '';
    $tags = !empty($_POST['tags']) ? trim($_POST['tags']) : '';
    $isBestSell = !empty($_POST['is_best_sell']) ? trim($_POST['is_best_sell']) : '';
    $isNew = !empty($_POST['is_new']) ? trim($_POST['is_new']) : '';
    $sortOder = !empty($_POST['sort_order']) ? trim($_POST['sort_order']) : '';
    $activeProduct = !empty($_POST['active']) ? trim($_POST['active']) : '';

    // Upload
    $fileName = $avatarFile['name'];
    $fileNameArr = explode('.', trim($fileName));
    $fileExt = end($fileNameArr);
    $fileBefore = sha1(uniqid());
    $fileName = $fileBefore . '.' . $fileExt;
    $allowedArr = ['jpg', 'png', 'gif', 'jpg', 'jpeg'];

    // Validate product's name
    if (!checkRequire($name)) {
        $errors['name'] = 'Please type name.';
    }

    // Validate product's category_id
    if (empty($categoryId)) {
        $errors['category_id'] = 'Please choose category.';
    }

    // Validate product's brand_id
    if (empty($brandId)) {
        $errors['brand_id'] = 'Please choose brand.';
    }

    // Validate product's price
    if (!checkRequire($price)) {
        $errors['price']['required'] = 'Please type price.';
    } else {
        if (strlen($price) <= 1) {
            $errors['price']['valid'] = 'Please re-type the price';
        }
    }

    // Validate product's sortOder
    if (!checkRequire($sortOder)) {
        $errors['sort_order']['required'] = 'Please type sort order.';
    }

    // Validate product's image
    if (isset($avatarFile['name']) && !empty($avatarFile['name'])) {
        // Validate upload
        if ($avatarFile['error'] == 4) {
            $errors['avatar']['required'] = 'Please upload file.';
        } else {
            if (!in_array($fileExt, $allowedArr)) {
                $errors['avatar']['allow_ext'] = 'Formatting is not allowed, only accepted ' . implode(', ', $allowedArr);
            }

            if (!empty($avatarFile['size'])) {
                $size = $avatarFile['size'];

                $sizeMb = $size / 1024 / 1024;
                $sizeMb = round($sizeMb, 2);
                if ($sizeMb > 2) {
                    $errors['avatar']['max_size'] = 'Size exceeds the allowed limit, can only be uploaded <= 2 MB';
                }
            } else {
                $errors['avatar']['file_error'] = 'File is error, please check';
            }
        }

        // Create new product when isset image and not found any error
        if (empty($errors)) {
            $imageUrl = $infoProduct['image'];

            if ($avatarFile['error'] == 0) {
                $dirUploads = __DIR__ . "/../../../common/uploads/products";
                if (!file_exists($dirUploads)) {
                    mkdir($dirUploads);
                }
                $imageUrl = time() . $fileName;
                move_uploaded_file($avatarFile['tmp_name'], $dirUploads . '/' . $imageUrl);
            }

            deleteImageProductUpload($connection, $id);

            $data = [
                $categoryId,
                $brandId,
                $name,
                $imageUrl,
                $price,
                $oldPrice,
                $description,
                $tags,
                $isBestSell,
                $isNew,
                $sortOder,
                $activeProduct,
                $id
            ];

            updateProduct($connection, $data);

            $_SESSION['success'] = 'Edit product ' . $name . ' successfully';

            header('location: product_list.php');
            exit();
        }
    } else {
        $data = [
            $categoryId,
            $brandId,
            $name,
            $price,
            $oldPrice,
            $description,
            $tags,
            $isBestSell,
            $isNew,
            $sortOder,
            $activeProduct,
            $id
        ];

        updateProductWithOutImg($connection, $data);

        $_SESSION['success'] = 'Edit product ' . $name . ' successfully';

        header('location: product_list.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Edit Product';

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

        $('#confirmationTags').tagit({
            availableTags: sampleTags,
            removeConfirmation: true
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
                            <div class="widget-header"> <i class="icon-book"></i>
                                <h3>Edit product's id <b style="color:#F30"><?php echo $infoProduct['id']; ?></b></h3>
                            </div>
                            <!-- /widget-header -->

                            <div class="widget-content">
                                <form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <fieldset>
                                        <br>
                                        <div class="control-group">
                                            <label class="control-label" for="name">Name <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="name" name="name" value="<?php echo $infoProduct['name']; ?>">
                                                <?php
                                                echo !empty($errors['name']) ? '<br><em class="error-notice">&nbsp;' . $errors['name'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="avatar">Avatar <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="file" class="span4" id="avatar" name="avatar">
                                                <?php
                                                echo !empty($errors['avatar']['required']) ? '<br><em class="error-notice">&nbsp;' . $errors['avatar']['required'] . '</em>' : '';
                                                echo !empty($errors['avatar']['allow_ext']) ? '<br><em class="error-notice">&nbsp;' . $errors['avatar']['allow_ext'] . '</em>' : '';
                                                echo !empty($errors['avatar']['max_size']) ? '<br><em class="error-notice">&nbsp;' . $errors['avatar']['max_size'] . '</em>' : '';
                                                echo !empty($errors['avatar']['file_error']) ? '<br><em class="error-notice">&nbsp;' . $errors['avatar']['file_error'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->


                                            <div class="control-group">
                                                <label class="control-label"></label>
                                                <div class="controls">
                                                    <?php if (!empty($infoProduct['image'])) { ?>
                                                        <img style="margin: 10px 0px; border-radius: 3px" src="../../../common/uploads/products/<?php echo $infoProduct['image']; ?>" height="70" width="100" />
                                                    <?php } ?>
                                                </div>
                                                <!-- /controls -->
                                            </div> <!-- /control-group -->

                                        </div> <!-- /control-group -->
                                        <div class="control-group">
                                            <label class="control-label" for="active">Category <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <select name="category_id" class="span4">
                                                    <?php if (count($nameCategory) > 0) { ?>
                                                        <option value="">-- All Categories -- </option>
                                                        <?php foreach ($nameCategory as $name) { ?>
                                                            <option <?php echo (!empty($name['name']) && $name['name'] == $infoProduct['category_name']) ? 'selected=\"selected\"' : '';  ?> value="<?php echo $name['id'] ?>"><?php echo $name['name']; ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option value="" class="text-center">No exist any categories</option>
                                                    <?php }  ?>
                                                </select>
                                                <?php
                                                echo !empty($errors['category_id']) ? '<br><em class="error-notice">&nbsp;' . $errors['category_id'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Brand <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <select name="brand_id" class="span4">
                                                    <?php if (count($nameBrand) > 0) { ?>
                                                        <option value="">-- All Brands -- </option>
                                                        <?php foreach ($nameBrand as $name) { ?>
                                                            <option <?php echo (!empty($name['name']) && $name['name'] == $infoProduct['brand_name']) ? 'selected=\"selected\"' : '';  ?> value="<?php echo $name['id'] ?>"><?php echo $name['name']; ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option value="" class="text-center">No exist any brands</option>
                                                    <?php }  ?>
                                                </select>
                                                <?php
                                                echo !empty($errors['brand_id']) ? '<br><em class="error-notice">&nbsp;' . $errors['brand_id'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Best Sell</label>
                                            <div class="controls">
                                                <select name="is_best_sell" class="span4">
                                                    <option <?php echo ($infoProduct['is_best_sell'] == '0') ? 'selected=\"selected\"' : '';  ?> value="0" class="text-center">No</option>
                                                    <option <?php echo ($infoProduct['is_best_sell'] == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">Yes</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">New</label>
                                            <div class="controls">
                                                <select name="is_new" class="span4">
                                                    <option <?php echo ($infoProduct['is_new'] == '0') ? 'selected=\"selected\"' : '';  ?> value="0" class="text-center">No</option>
                                                    <option <?php echo ($infoProduct['is_new'] == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">Yes</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Active</label>
                                            <div class="controls">
                                                <select name="active" class="span4">
                                                    <option <?php echo ($infoProduct['active'] == '0') ? 'selected=\"selected\"' : '';  ?> value="0" class="text-center">Inactive</option>
                                                    <option <?php echo ($infoProduct['active'] == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">Active</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="price">Price <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input class="span4" id="price" type="number" name="price" value="<?php echo $infoProduct['price']; ?>">
                                                <?php
                                                echo !empty($errors['price']['required']) ? '<br><em class="error-notice">&nbsp;' . $errors['price']['required'] . '</em>' : '';
                                                echo !empty($errors['price']['valid']) ? '<br><em class="error-notice">&nbsp;' . $errors['price']['valid'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="price">Old price</label>
                                            <div class="controls">
                                                <input class="span4" id="price" type="number" name="old_price" value="<?php echo $infoProduct['old_price']; ?>">
                                                <?php
                                                echo !empty($errors['price']['required']) ? '<br><em class="error-notice">&nbsp;' . $errors['price']['required'] . '</em>' : '';
                                                echo !empty($errors['price']['valid']) ? '<br><em class="error-notice">&nbsp;' . $errors['price']['valid'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="tag">Tags</label>
                                            <div class="controls">
                                                <div class="span4" style="margin-left: 0px">
                                                    <input type="text" id="confirmationTags" name="tags" value="<?php echo $infoProduct['tags']; ?>">
                                                </div>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="sort_order">Sort Order <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input class="span4" id="sort_order" type="number" name="sort_order" value="<?php echo $infoProduct['sort_order']; ?>">
                                                <?php
                                                echo !empty($errors['sort_order']['required']) ? '<br><em class="error-notice">&nbsp;' . $errors['sort_order']['required'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="row">
                                            <div class="span8">
                                                <div class="control-group">
                                                    <label class="control-label" for="description">Description</label>
                                                    <div class="controls">
                                                        <textarea name="description" class="ckeditor" id="description" cols="30" rows="10"><?php echo $infoProduct['description']; ?></textarea>
                                                    </div> <!-- /controls -->
                                                </div> <!-- /control-group -->
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Edit</button>
                                            &ensp;
                                            <a class="btn" href="./product_list.php">Cancel</a></button>
                                        </div> <!-- /form-actions -->
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