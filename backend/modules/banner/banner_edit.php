<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/banner_funcs.php';
require_once '../../common/validate.php';

session_start();

// Check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

// Check and validate banner's id
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: banner_list.php');
    exit();
}

$id = !empty($_GET['id']) ? trim($_GET['id']) : '';

$infoBanner = getInfoBannerEdit($connection, $id, $activeDelete);

if (empty($infoBanner)) {
    header('location: brand_list.php');
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

    // Upload image
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

    // Validate banner's sortOder
    if (!checkRequire($sortOder)) {
        $errors['sort_order']['required'] = 'Please type sort order.';
    }

    // Validate banner's upload
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

        // Update banner when not found any errors
        if (empty($errors)) {
            $imageUrl = $infoBanner['image_url'];

            if ($avatarFile['error'] == 0) {
                $dirUploads = __DIR__ . "/../../../common/uploads/banners";
                if (!file_exists($dirUploads)) {
                    mkdir($dirUploads);
                }
                $imageUrl = time() . $fileName;
                move_uploaded_file($avatarFile['tmp_name'], $dirUploads . '/' . $imageUrl);
            }

            deleteImageBannerUpload($connection, $id);

            $data = [
                $title,
                $content,
                $imageUrl,
                $sortOder,
                $activeBanner,
                $id
            ];
            updateBanner($connection, $data);

            $_SESSION['success'] = 'Update banner ' . $title . ' successfully';

            header('location: banner_list.php');
            exit();
        }
    } else {
        $data = [
            $title,
            $content,
            $sortOder,
            $activeBanner,
            $id
        ];
        updateBannerWithOutImage($connection, $data);

        $_SESSION['success'] = 'Update banner ' . $title . ' successfully';

        header('location: banner_list.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Edit Banner';

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
                            <div class="widget-header"> <i class="icon-th-list"></i>
                                <h3>Edit banner's id <b style="color:#F30"><?php echo $infoBanner['id']; ?></b></h3>
                            </div>
                            <!-- /widget-header -->

                            <div class="widget-content">
                                <form id="edit-profile" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <fieldset>
                                        <br>
                                        <div class="control-group">
                                            <label class="control-label" for="title">Title <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="title" name="title" value="<?php echo $infoBanner['title']; ?>">
                                                <?php
                                                echo !empty($errors['title']) ? '<br><em class="error-notice">&nbsp;' . $errors['title'] . '</em>' : '';
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
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="content">Content <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="content" name="content" value="<?php echo $infoBanner['content']; ?>">
                                                <?php
                                                echo !empty($errors['content']) ? '<br><em class="error-notice">&nbsp;' . $errors['content'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label"></label>
                                            <div class="controls">
                                                <?php if (!empty($infoBanner['image_url'])) { ?>
                                                    <img style="margin: 10px 0px; border-radius: 3px" src="../../../common/uploads/banners/<?php echo $infoBanner['image_url']; ?>" height="70" width="100" />
                                                <?php } ?>
                                            </div>
                                            <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="sort_order">Sort Order <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input class="span4" id="sort_order" type="number" name="sort_order" value="<?php echo $infoBanner['sort_order']; ?>">
                                                <?php
                                                echo !empty($errors['sort_order']['required']) ? '<br><em class="error-notice">&nbsp;' . $errors['sort_order']['required'] . '</em>' : '';
                                                echo !empty($errors['sort_order']['exist']) ? '<br><em class="error-notice">&nbsp;' . $errors['sort_order']['exist'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Active</label>
                                            <div class="controls">
                                                <select name="active">
                                                    <option <?php echo ($infoBanner['active'] == '0') ? 'selected=\"selected\"' : '';  ?> value="0" class="text-center">INACTIVE</option>
                                                    <option <?php echo ($infoBanner['active'] == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">ACTIVE</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Save</button>
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