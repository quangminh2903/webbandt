<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/brand_funcs.php';
require_once '../../common/validate.php';

session_start();

// Check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

// Check and validate id
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: brand_list.php');
    exit();
}
$id = !empty($_GET['id']) ? trim($_GET['id']) : '';

$infoBrand = getInfoBrandEdit($connection, $id, $activeDelete);

if (empty($infoBrand)) {
    header('location: brand_list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Get form data
    $name = !empty($_POST['name']) ? trim($_POST['name']) : '';
    $avatarFile = !empty($_FILES['avatar']) ? $_FILES['avatar'] : '';
    $link = !empty($_POST['link']) ? trim($_POST['link']) : '';
    $sortOder = !empty($_POST['sort_order']) ? trim($_POST['sort_order']) : '';
    $activeBrand = !empty($_POST['active']) ? trim($_POST['active']) : '';

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

        // Update brand when not found any errors
        if (empty($errors)) {
            $imageUrl = $infoBrand['image_url'];

            if ($avatarFile['error'] == 0) {
                $dirUploads = __DIR__ . "/../../../common/uploads/brands";
                if (!file_exists($dirUploads)) {
                    mkdir($dirUploads);
                }
                $imageUrl = time() . $fileName;
                move_uploaded_file($avatarFile['tmp_name'], $dirUploads . '/' . $imageUrl);
            }

            deleteImageBrandUpload($connection, $id);

            $data = [
                $name,
                $imageUrl,
                $link,
                $sortOder,
                $activeBrand,
                $id
            ];
            updateBrand($connection, $data);

            $_SESSION['success'] = 'Update brand ' . $name . ' successfully';

            header('location: brand_list.php');
            exit();
        }
    } else {
        $data = [
            $link,
            $sortOder,
            $activeBrand,
            $id
        ];
        updateBrandWithOutImage($connection, $data);

        $_SESSION['success'] = 'Update brand ' . $name . ' successfully';

        header('location: brand_list.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Edit Brand';

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
                                <h3>Edit brand's id <b style="color:#F30"><?php echo $infoBrand['id']; ?></b></h3>
                            </div>
                            <!-- /widget-header -->

                            <div class="widget-content">
                                <form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <fieldset>
                                        <br>
                                        <div class="control-group">
                                            <label class="control-label" for="username">Name</label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="username" name="name" value="<?php echo $infoBrand['name']; ?>" readonly>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

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
                                                <?php if (!empty($infoBrand['image_url'])) { ?>
                                                    <img style="margin: 10px 0px; border-radius: 3px" src="../../../common/uploads/brands/<?php echo $infoBrand['image_url']; ?>" height="70" width="100" />
                                                <?php } ?>
                                            </div>
                                            <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="link">Link  </label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="link" name="link" value="<?php echo $infoBrand['link']; ?>">
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="sort_order">Sort Order <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input class="span4" id="sort_order" type="number" name="sort_order" value="<?php echo $infoBrand['sort_order']; ?>">
                                                <?php
                                                echo !empty($errors['sort_order']['required']) ? '<br><em class="error-notice">&nbsp;' . $errors['sort_order']['required'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Active  </label>
                                            <div class="controls">
                                            <select name="active" class="span4">
                                                    <option <?php echo ($infoBrand['active'] == '0') ? 'selected=\"selected\"' : '';  ?> value="0" class="text-center">INACTIVE</option>
                                                    <option <?php echo ($infoBrand['active'] == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">ACTIVE</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            &ensp;
                                            <a class="btn" href="./brand_list.php">Cancel</a></button>
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