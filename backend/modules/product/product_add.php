<?php
require_once '../../../common/configs/connect.php';
require_once '../../../common/configs/constant.php';
require_once '../../funcs/product_funcs.php';
require_once '../../common/validate.php';

session_start();

// check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

// get category's name
$nameCategory = getNameCategory($connection, $active);

// get brand's name
$nameBrand = getNameBrand($connection, $active);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // get form data
    $name = !empty($_POST['name']) ? trim($_POST['name']) : '';
    $categoryId = !empty($_POST['category_id']) ? trim($_POST['category_id']) : '';
    $brandId = !empty($_POST['brand_id']) ? trim($_POST['brand_id']) : '';
    $avatarFile = !empty($_FILES['avatar']) ? $_FILES['avatar'] : '';
    $price = !empty($_POST['price']) ? trim($_POST['price']) : '';
    $description = !empty($_POST['description']) ? trim($_POST['description']) : '';
    $tags = !empty($_POST['tags']) ? trim($_POST['tags']) : '';
    $isBestSale = !empty($_POST['is_best_sale']) ? trim($_POST['is_best_sale']) : '';
    $isNew = !empty($_POST['is_new']) ? trim($_POST['is_new']) : '';
    $sortOder = !empty($_POST['sort_order']) ? trim($_POST['sort_order']) : '';
    $activeProduct = !empty($_POST['active']) ? trim($_POST['active']) : '';

    // upload
    $fileName = $avatarFile['name'];
    $fileNameArr = explode('.', trim($fileName));
    $fileExt = end($fileNameArr);
    $fileBefore = sha1(uniqid());
    $fileName = $fileBefore . '.' . $fileExt;
    $allowedArr = ['jpg', 'png', 'gif', 'jpg', 'jpeg'];

    // validate product's name
    if (!checkRequire($name)) {
        $errors['name'] = 'Please type name.';
    }

    // validate product's category_id
    if (empty($categoryId)) {
        $errors['category_id'] = 'Please choose category.';
    }

    // validate product's brand_id
    if (empty($brandId)) {
        $errors['brand_id'] = 'Please choose brand.';
    }

    // validate upload
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

    // validate product's price
    if (!checkRequire($price)) {
        $errors['price']['required'] = 'Please type price.';
    } else {
        if (strlen($price) <= 1) {
            $errors['price']['valid'] = 'Please re-type the price';
        }
    }

    // validate product's sortOder
    if (!checkRequire($sortOder)) {
        $errors['sort_order']['required'] = 'Please type sort order.';
    }

    // create new product when not found any error
    if (empty($errors)) {
        $imageUrl = '';
        if ($avatarFile['error'] == 0) {
            $dirUploads = __DIR__ . "/../../../common/uploads/products";
            if (!file_exists($dirUploads)) {
                mkdir($dirUploads);
            }
            $imageUrl = time() . $fileName;
            move_uploaded_file($avatarFile['tmp_name'], $dirUploads . '/' . $imageUrl);
        }

        $data = [
            $categoryId,
            $brandId,
            $name,
            $imageUrl,
            $price,
            $description,
            $tags,
            $isBestSale,
            $isNew,
            $sortOder,
            $activeProduct
        ];
        createProduct($connection, $data);

        $_SESSION['success'] = 'Create new product ' . $name . ' successfully';

        header('location: product_list.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Add New Product';

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
                                <h3>Add new product</h3>
                            </div>
                            <!-- /widget-header -->

                            <div class="widget-content">
                                <form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <fieldset>
                                        <br>
                                        <div class="control-group">
                                            <label class="control-label" for="name">Name <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="name" name="name" value="<?php echo !empty($name) ? $name : ''; ?>">
                                                <?php
                                                echo !empty($errors['name']) ? '<br><em class="error-notice">&nbsp;' . $errors['name'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="avatar">Avatar <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="file" class="span4" id="avatar" name="avatar">
                                                <?php require_once '../../lib/error_avatar.php'; ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Category <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <select name="category_id" class="span4">
                                                    <?php if (count($nameCategory) > 0) { ?>
                                                        <option value="">-- All Categories -- </option>
                                                        <?php foreach ($nameCategory as $name) {
                                                            $selected = ($categoryId == $name['id']) ? 'selected' : '';
                                                        ?>
                                                            <option <?php echo $selected;  ?> value="<?php echo $name['id']; ?>"><?php echo $name['name']; ?></option>
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
                                                        <?php
                                                        foreach ($nameBrand as $name) {
                                                            $selected = ($brandId == $name['id']) ? 'selected' : '';
                                                        ?>
                                                            <option <?php echo $selected;  ?> value="<?php echo $name['id'] ?>"><?php echo $name['name']; ?></option>
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
                                                <select name="is_best_sale" class="span4">
                                                    <option value="0" class="text-center">No</option>
                                                    <option <?php echo (!empty($isBestSale) && $isBestSale == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">Yes</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">New</label>
                                            <div class="controls">
                                                <select name="is_new" class="span4">
                                                    <option value="0" class="text-center">No</option>
                                                    <option <?php echo (!empty($isNew) && $isNew == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">Yes</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Active</label>
                                            <div class="controls">
                                                <select name="active" class="span4">
                                                    <option value="0" class="text-center">Inactive</option>
                                                    <option <?php echo (!empty($activeProduct) && $activeProduct == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">Active</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="price">Price <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input class="span4" id="price" type="number" name="price" value="<?php echo !empty($price) ? $price : ''; ?>">
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
                                                    <input type="text" id="confirmationTags" name="tags" value="<?php echo !empty($tags) ? $tags : ''; ?>">
                                                </div>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="sort_order">Sort Order <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input class="span4" id="sort_order" type="number" name="sort_order" value="<?php echo !empty($sortOder) ? $sortOder : ''; ?>">
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
                                                        <textarea name="description" class="ckeditor" id="description" cols="30" rows="10"></textarea>
                                                    </div> <!-- /controls -->
                                                </div> <!-- /control-group -->
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Create</button>
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