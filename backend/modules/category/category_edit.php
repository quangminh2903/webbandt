<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/category_funcs.php';
require_once '../../common/validate.php';

session_start();

// Check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

// Check and validate id
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: category_list.php');
    exit();
}

$id = !empty($_GET['id']) ? trim($_GET['id']) : '';

$infoCategories = getInfoCategoriesEdit($connection, $id, $activeDelete);

if (empty($infoCategories)) {
    header('location: category_list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Get form data
    $name = !empty($_POST['name']) ? trim($_POST['name']) : '';
    $sortOder = !empty($_POST['sort_order']) ? trim($_POST['sort_order']) : '';
    $activeCategory = !empty($_POST['active']) ? trim($_POST['active']) : '';

    // Validate category's name
    if (!checkRequire($name)) {
        $errors['name'] = 'Please type name.';
    }

    // Validate category's sort order
    if (!checkRequire($sortOder)) {
        $errors['sort_order']['required'] = 'Please type sort order.';
    }

    // Edit category when not found any errors
    if (empty($errors)) {
        $dataUpdate = [
            $name,
            $sortOder,
            $activeCategory,
            $id
        ];

        updateCategories($connection, $dataUpdate);

        $_SESSION['success'] = 'Update category successfully';

        header('location: category_list.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Edit Category';

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
                            <div class="widget-header"> <i class="icon-list-alt"></i>
                                <h3>Edit category's id <b style="color:#F30"><?php echo $infoCategories['id']; ?></b></h3>
                            </div>
                            <!-- /widget-header -->

                            <div class="widget-content">
                                <form id="edit-profile" class="form-horizontal" action="" method="post">
                                    <fieldset>
                                        <br>
                                        <div class="control-group">
                                            <label class="control-label" for="username">Name <span class="error-notice" style="color:red;">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="username" name="name" value="<?php echo $infoCategories['name']; ?>">
                                                <?php
                                                echo !empty($errors['name']) ? '<br><em class="error-notice" style="color:red;">&nbsp;' . $errors['name'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="sort_order">Sort Order <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input class="span4" id="sort_order" type="number" name="sort_order" value="<?php echo $infoCategories['sort_order']; ?>">
                                                <?php
                                                echo !empty($errors['sort_order']['required']) ? '<br><em class="error-notice" style="color:red;">&nbsp;' . $errors['sort_order']['required'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Active</label>
                                            <div class="controls">
                                                <select name="active" class="span4">
                                                    <option <?php echo ($infoCategories['active'] == '0') ? 'selected=\"selected\"' : '';  ?> value="0" class="text-center">INACTIVE</option>
                                                    <option <?php echo ($infoCategories['active'] == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">ACTIVE</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->


                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            &ensp;
                                            <a class="btn" href="./category_list.php">Cancel</a></button>
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