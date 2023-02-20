<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/order_funcs.php';

session_start();

// Check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Get form data
    $search = !empty($_GET['search']) ? trim($_GET['search']) : '';

    // Pagination
    $listOrders = getAllOrders($connection);

    $perPage = 10;

    $allNum = count($listOrders);
    $maxPage = ceil($allNum / $perPage);

    if (!empty($_GET['page'])) {
        $page = trim($_GET['page']);
    } else {
        $page = 1;
        if ($page < 1 || $page > $maxPage) {
            $page = 1;
        }
    }

    $offset = ($page - 1) * $perPage;

    if (!empty($search) && $search !== '') {
        // get list all orders when search
        $listAllOrders = searchOrders($connection, $search, $offset, $perPage);
    } else {
        // get list all orders
        $listAllOrders = getAllOrdersPagination($connection, $offset, $perPage);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'List Orders';

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
                            <div class="widget-header"> <i class="icon-shopping-cart"></i>
                                <h3>Orders</h3>
                            </div>
                            <!-- /widget-header -->
                            <br>

                            <?php
                            require_once '../../common/search.php';
                            ?>

                            <div class="widget-content">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="td-actions" style="width: 5%;">No</th>
                                            <th class="td-actions" style="width: 10%;">Customer Name</th>
                                            <th class="td-actions" style="width: 13%;">Customer Phone</th>
                                            <th class="td-actions" style="width: 15%;">Customer Email</th>
                                            <th class="td-actions" style="width: 11%;">Total Money</th>
                                            <th class="td-actions" style="width: 10%;">Total Products</th>
                                            <th class="td-actions" style="width: 16%;">Date</th>
                                            <th class="td-actions" style="width: 5%;">Status</th>
                                            <th class="td-actions" style="width: 15%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($listAllOrders) > 0) {
                                            foreach ($listAllOrders as $listNumber => $listOrder) {
                                                if ($listOrder['status'] == '0') {
                                                    $listOrder['status'] = 'Processing';

                                                    $classActive = 'btn btn-warning';

                                                    $action = '<a title="Action complete order id ' . $listOrder['id'] . '" class="btn btn-success" href="./order_action.php?action=complete&order-id=' . $listOrder['id'] . '"><i class="icon-ok"></i></a>
                                                    <a title="Action cancel order id ' . $listOrder['id'] . '" class="btn btn-danger" href="./order_action.php?action=cancel&order-id=' . $listOrder['id'] . '"><i class="icon-remove"></i></a>';
                                                } elseif ($listOrder['status'] == '1') {
                                                    $listOrder['status'] = 'Completed';

                                                    $classActive = 'btn btn-success';

                                                    $action = '<a title="Action undo order id ' . $listOrder['id'] . '" class="btn btn-danger" href="./order_action.php?action=undo&order-id=' . $listOrder['id'] . '"><i class="icon-undo"></i></a>';
                                                } else {
                                                    $listOrder['status'] = 'Cancel';
                                                    $classActive = 'btn btn-danger';
                                                    $action = '<a title="Action undo order id ' . $listOrder['id'] . '" class="btn btn-danger" href="./order_action.php?action=undo&order-id=' . $listOrder['id'] . '"><i class="icon-undo"></i></a>';
                                                }

                                                $numList = ($page - 1) * $perPage + $listNumber + 1;

                                                echo '<tr>';
                                                echo ' <td class="td-actions">' . $numList . '</td>';
                                                echo ' <td class="td-actions">' . $listOrder['customer_name'] . '</td>';
                                                echo ' <td class="td-actions">' . $listOrder['customer_phone'] . '</td>';
                                                echo ' <td class="td-actions">' . $listOrder['customer_email'] . '</td>';
                                                echo ' <td class="td-actions">' . $listOrder['total_money'] . '</td>';
                                                echo ' <td class="td-actions">' . $listOrder['total_products'] . '</td>';
                                                echo ' <td class="td-actions">' . $listOrder['created_date'] . '</td>';
                                                echo ' <td class="td-actions"><div class="' . $classActive . '">' . $listOrder['status'] . '</div></td>';

                                                echo ' <td class="td-actions">'
                                                    . $action . '
                                                <a title="Order detail order id ' . $listOrder['id'] . '" class="btn btn-info" href="./order_items_list.php?order-id=' . $listOrder['id'] . '"><i class="icon-info"></i></a>
                                                </td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<td colspan="9" class="td-actions">No data displayed</td>';
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
                <!-- /row -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?php
                        if ($page > 1) {
                            $prevPage = $page - 1;
                            if (empty($search)) {
                                echo '<li class="page-item">
                                    <a class="page-link btn btn-invert" href="order_list.php?page=' . $prevPage . '">&laquo;</a>
                                </li>';
                            } else {
                                echo '<li class="page-item">
                                    <a class="page-link btn btn-invert" href="order_list.php?search=' . $search . '&page=' . $prevPage . '">&laquo;</a>
                                </li>';
                            }
                        }

                        if (empty($search)) {
                            for ($index = 1; $index <= $maxPage; $index++) {
                                if ($maxPage == 1 && $page = 1) {
                                    echo '<li></li>';
                                } else { ?>
                                    <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                        <a class="page-link btn btn-invert" href="<?php echo 'order_list.php?page=' . $index; ?>">
                                            <?php echo $index; ?>
                                        </a>
                                    </li>
                                <?php }
                            }
                        } else {
                            $listAllOrders = countTotalSearchOrders($connection, $search);

                            $allNum = count($listAllOrders);
                            $maxPage = ceil($allNum / $perPage);

                            for ($index = 1; $index <= $maxPage; $index++) {
                                if ($maxPage == 1 && $page = 1) {
                                    echo '<li></li>';
                                } else { ?>
                                    <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                        <a class="page-link btn btn-invert" href="<?php echo 'order_list.php?search=' . $search . '&page=' . $index; ?>">
                                            <?php echo $index; ?>
                                        </a>
                                    </li>
                        <?php }
                            }
                        }

                        $nextPage = $page + 1;
                        if ($page < $maxPage) {
                            if (!empty($search)) {
                                echo '<li class="page-item">
                                    <a class="page-link btn btn-invert" href="order_list.php?search=' . $search . '&page=' . $nextPage . '">&raquo;</a>
                                </li>';
                            } else {
                                echo '<li class="page-item">
                                    <a class="page-link btn btn-invert" href="order_list.php?page=' . $nextPage . '">&raquo;</a>
                                </li>';
                            }
                        }
                        ?>

                    </ul>
                </nav>
            </div>
            <!-- /container -->
        </div>
        <!-- /main-inner -->
    </div>
    <!-- /main -->

    <!-- DELETE POP UP FORM -->
    <div id="deletemodal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="exampleModelLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Delete Brand</h3>
        </div>
        <form action="./brand_delete.php" method="post">
            <div class="modal-body">
                <input type="hidden" name="delete_id" id="delete_id" value="<?php echo $listBrand['id']; ?>">
                <p>Do you want delete this brand ?</p>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>

    <!-- END DELETE POP UP FORM -->

    <?php require '../../common/footer_top.php'; ?>

    <?php require '../../common/footer_bottom.php'; ?>

    <?php require '../../common/footer_lib.php'; ?>

    <script>
        $(document).ready(function() {
            $('.deletebtn').on('click', function() {
                $('#deletemodal').modal('show');

                $tr = $(this).closest('tr');

                let data = $tr.children("td").map(function() {
                    return $(this);
                });

                let tr = data[7];
                let dom = tr['context']['innerHTML'];

                let replaced = dom.replace(/\D/g, '');

                $('#delete_id').val(replaced);
            });
        });
    </script>

</body>

</html>