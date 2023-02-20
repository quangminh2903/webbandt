<?php
require_once '../../../common/configs/connect.php';
require_once '../../funcs/order_funcs.php';

session_start();

// check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

// Check order id
if (empty($_GET['order-id']) || !is_numeric($_GET['order-id'])) {
    $_SESSION['error'] = 'Not exist order id';

    header('location: order_list.php');
    exit();
}

$orderId = !empty($_GET['order-id']) ? trim($_GET['order-id']) : '';

// Get info order item
$inforOrderItems = getInfoOrders($connection, $orderId);

if (empty($inforOrderItems)) {
    $_SESSION['error'] = 'Not exist order id';

    header('location: order_list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // get form data
    $search = !empty($_GET['search']) ? trim($_GET['search']) : '';

    $listOrderItems = getAllOrderDetail($connection, $orderId);
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'List Orders';

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
                            <div class="widget-header"> <i class="icon-shopping-cart"></i>
                                <h3>Order details</h3>
                            </div>
                            <!-- /widget-header -->
                            <br>
                            <button class="btn btn-primary"><a class="a-button" href="./order_list.php"><i class="icon-arrow-left"></i>&ensp;&nbsp;Back to list orders</a></button>
                            <br><br>
                            <div class="widget-content">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="td-actions">No</th>
                                            <th class="td-actions">Product Image</th>
                                            <th class="td-actions">Product Name</th>
                                            <th class="td-actions">Product Price</th>
                                            <th class="td-actions">Product Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($listOrderItems) > 0) {
                                            foreach ($listOrderItems as $listNumber => $listOrder) {

                                                $numList = $listNumber + 1;

                                                echo '<tr>';
                                                echo ' <td class="td-actions">' . $numList . '</td>';
                                                echo ' <td class="td-actions">' . ($listOrder['product_image'] == '' ? '<div style="width: 100px;"></div>' : '<img style="width: 100px;" src="../../../common/uploads/products/' . $listOrder['product_image'] . '">') . '</td>';
                                                echo ' <td class="td-actions">' . $listOrder['product_name'] . '</td>';
                                                echo ' <td class="td-actions">$ ' . number_format($listOrder['product_price']) . '</td>';
                                                echo ' <td class="td-actions">' . $listOrder['product_quantity'] . '</td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<td colspan="5" class="td-actions">No data displayed</td>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /widget-content -->
                        </div>
                    </div>
                    <!-- /span6 -->
                </div>

            </div>
            <!-- /container -->
        </div>
        <!-- /main-inner -->
    </div>
    <!-- /main -->
    </div>
    </div>

    <?php require '../../common/footer_top.php'; ?>

    <?php require '../../common/footer_bottom.php'; ?>

    <?php require '../../common/footer_lib.php'; ?>

</body>

</html>