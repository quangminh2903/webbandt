<?php
require_once '../../../common/configs/connect.php';
require_once '../../funcs/banner_funcs.php';
require_once '../../common/validate.php';

session_start();

// Check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Get form data
    $title = !empty($_POST['title']) ? trim($_POST['title']) : '';
    $content = !empty($_POST['content']) ? trim($_POST['content']) : '';
    $avatarFile = !empty($_FILES['avatar']) ? $_FILES['avatar'] : '';
    $sortOder = !empty($_POST['sort_order']) ? trim($_POST['sort_order']) : '';
    $activeBanner = !empty($_POST['active']) ? trim($_POST['active']) : '';

    // Upload file
    $fileName = $avatarFile['name'];
    $fileNameArr = explode('.', trim($fileName));
    $fileExt = end($fileNameArr);
    $fileBefore = sha1(uniqid());
    $fileName = $fileBefore . '.' . $fileExt;
    $allowedArr = ['jpg', 'png', 'gif', 'jpg', 'jpeg'];

    // Validate banner's title
    if (!checkRequire($title)) {
        $errors['title'] = 'Please type title.';
    }

    // Validate banner's content
    if (!checkRequire($content)) {
        $errors['content'] = 'Please type content.';
    }

    // Validate banner's upload
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

    // Validate banner's sort order
    if (!checkRequire($sortOder)) {
        $errors['sort_order']['required'] = 'Please type sort order.';
    }

    // Create banner when not found any error
    if (empty($errors)) {
        $imageUrl = '';
        if ($avatarFile['error'] == 0) {
            $dirUploads = __DIR__ . "/../../../common/uploads/banners";
            if (!file_exists($dirUploads)) {
                mkdir($dirUploads);
            }
            $imageUrl = time() . $fileName;
            move_uploaded_file($avatarFile['tmp_name'], $dirUploads . '/' . $imageUrl);
        }

        $data = [
            $title,
            $content,
            $imageUrl,
            $sortOder,
            $activeBanner
        ];
        createBanner($connection, $data);

        $_SESSION['success'] = 'Create new banner ' . $title . ' successfully';

        header('location: banner_list.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Add New Banner';

require '../../common/header_lib.php';
?>

<?php require '../../../common/configs/constant.php'; ?>

<body>
    <?php require '../../common/navbar.php'; ?>

    <?php require '../../common/menu.php'; ?>

    <div class="main">
        <div class="main-inner">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="widget widget-table action-table">
                            <div class="widget-header"> <i class="icon-th-list"></i>
                                <h3>Add new banner</h3>
                            </div>
                            <!-- /widget-header -->

                            <div class="widget-content">
                                <form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <fieldset>
                                        <br>
                                        <div class="control-group">
                                            <label class="control-label" for="title">Title <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="title" name="title" value="<?php echo !empty($title) ? $title : ''; ?>">
                                                <?php
                                                echo !empty($errors['title']) ? '<br><em class="error-notice">&nbsp;' . $errors['title'] . '</em>' : '';
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
                                            <label class="control-label" for="content">Content <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="content" name="content" value="<?php echo !empty($content) ? $content : ''; ?>">
                                                <?php
                                                echo !empty($errors['content']) ? '<br><em class="error-notice">&nbsp;' . $errors['content'] . '</em>' : '';
                                                ?>
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

                                        <div class="control-group">
                                            <label class="control-label" for="active">Active</label>
                                            <div class="controls">
                                                <select name="active" class="span4">
                                                    <option value="0" class="text-center">INACTIVE</option>
                                                    <option <?php echo (!empty($activeBanner) && $activeBanner == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">ACTIVE</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Create</button>
                                            &ensp;
                                            <a class="btn" href="./banner_list.php">Cancel</a>
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