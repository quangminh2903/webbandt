<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/product_funcs.php';
require_once '../../funcs/gallery_funcs.php';
require_once '../../common/validate.php';

session_start();

// Check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

// Get id product_id
if (empty($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    header('location: ../product/product_list.php');
    exit();
}
$productId = !empty($_GET['product_id']) ? trim($_GET['product_id']) : '';

$infoProduct = getInfoProduct($connection, $productId);

// Check and validate id
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: gallery_list.php?product_id=' . $productId . '');
    exit();
}
$id = !empty($_GET['id']) ? trim($_GET['id']) : '';

$infoGallery = getInfoGalleryEdit($connection, $id, $activeDelete);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Get form data
    $avatarFile = !empty($_FILES['avatar']) ? $_FILES['avatar'] : '';
    $sortOder = !empty($_POST['sort_order']) ? trim($_POST['sort_order']) : '';
    $activeGallery = !empty($_POST['active']) ? trim($_POST['active']) : '';

    // Upload image
    $fileName = $avatarFile['name'];
    $fileNameArr = explode('.', trim($fileName));
    $fileExt = end($fileNameArr);
    $fileBefore = sha1(uniqid());
    $fileName = $fileBefore . '.' . $fileExt;
    $allowedArr = ['jpg', 'png', 'gif', 'jpg', 'jpeg'];

    // Validate sort order
    if (!checkRequire($sortOder)) {
        $errors['sort_order']['required'] = 'Please type sort order.';
    }

    // Validate avatar
    if (isset($avatarFile['name']) && !empty($avatarFile['name'])) {
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

        // Update gallery when not found any errors
        if (empty($errors)) {
            $imageUrl = $infoGallery['image_url'];

            if ($avatarFile['error'] == 0) {
                $dirUploads = __DIR__ . "/../../../common/uploads/galleries";
                if (!file_exists($dirUploads)) {
                    mkdir($dirUploads);
                }
                $imageUrl = time() . $fileName;
                move_uploaded_file($avatarFile['tmp_name'], $dirUploads . '/' . $imageUrl);
            }

            // Delete image gallery upload
            deleteImageGalleryUpload($connection, $id);

            $data = [
                $imageUrl,
                $sortOder,
                $activeGallery,
                $id
            ];

            updateGallery($connection, $data);

            $_SESSION['success'] = 'Update gallery successfully';

            header('location: gallery_list.php?product_id=' . $productId . '');
            exit();
        }
    } else {
        $data = [
            $sortOder,
            $activeGallery,
            $id
        ];

        updateGalleryWithOutImage($connection, $data);

        $_SESSION['success'] = 'Update gallery successfully';

        header('location: gallery_list.php?product_id=' . $productId . '');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Edit Gallery';

require '../../common/header_lib.php';
?>

<body>
    <?php require '../../common/navbar.php'; ?>

    <?php require '../../common/menu.php'; ?>

    <div class="main">
        <div class="main-inner">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="widget widget-table action-table">
                            <div class="widget-header"> <i class="icon-flag"></i>
                                <h3>Edit galleries's id <b style="color:#F30"><?php echo $infoGallery['id']; ?></b></h3>
                            </div>
                            <!-- /widget-header -->

                            <div class="widget-content">
                                <form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <fieldset>
                                        <br>

                                        <div class="control-group">
                                            <label class="control-label" for="avatar">Avatar <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="file" class="span4" id="avatar" name="avatar" value="">
                                                <?php require_once '../../lib/error_avatar.php'; ?>
                                            </div>
                                            <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label"></label>
                                            <div class="controls">
                                                <?php if (!empty($infoGallery['image_url'])) { ?>
                                                    <img style="margin: 10px 0px; border-radius: 3px" src="../../../common/uploads/galleries/<?php echo $infoGallery['image_url']; ?>" width="100" />
                                                <?php } ?>
                                            </div>
                                            <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="sort_order">Sort Order <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input class="span4" id="sort_order" type="number" name="sort_order" value="<?php echo $infoGallery['sort_order']; ?>">
                                                <?php
                                                echo !empty($errors['sort_order']['required']) ? '<br><em class="error-notice">&nbsp;' . $errors['sort_order']['required'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Active </label>
                                            <div class="controls">
                                                <select name="active" class="span4">
                                                    <option <?php echo ($infoGallery['active'] == '0') ? 'selected=\"selected\"' : '';  ?> value="0" class="text-center">INACTIVE</option>
                                                    <option <?php echo ($infoGallery['active'] == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">ACTIVE</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            &ensp;
                                            <a class="btn" href="gallery_list.php?product_id=<?php echo $productId; ?>">Cancel</a>
                                            </button>
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

    <?php require '../../common/footer_lib.php'; ?>
</body>

</html>