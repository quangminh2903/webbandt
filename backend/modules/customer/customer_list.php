<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/customer_func.php';

session_start();

// check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // get form data
    $search = !empty($_GET['search']) ? trim($_GET['search']) : '';

    // pagination
    $listCustomers = getAllCustomers($connection, $activeDelete);

    $perPage = 10;

    $allNum = count($listCustomers);
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
        // get all list customers when search
        $listAllCustomers = searchCustomers($connection, $search, $activeDelete, $offset, $perPage);
    } else {
        // get all list customers
        $listAllCustomers = getAllCustomersPagination($connection, $activeDelete, $offset, $perPage);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'List Customers';

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
                            <div class="widget-header"> <i class="icon-group"></i>
                                <h3>Customers</h3>
                            </div>
                            <!-- /widget-header -->

                            <br><br>

                            <?php
                            require_once '../../common/search.php';
                            ?>

                            <div class="widget-content">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="td-actions" style="width: 5%;">No</th>
                                            <th class="td-actions">Name</th>
                                            <th class="td-actions" style="width: 10%;">Phone</th>
                                            <th class="td-actions">Email</th>
                                            <th class="td-actions">Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($listAllCustomers) > 0) {
                                            foreach ($listAllCustomers as $listNumber => $listCustomer) {
                                                if ($listCustomer['active'] == '0') {
                                                    $listCustomer['active'] = 'Inactive';
                                                    $classActive = 'btn btn-danger';
                                                } elseif ($listCustomer['active'] == '1') {
                                                    $listCustomer['active'] = 'Active';
                                                    $classActive = 'btn btn-success';
                                                } else {
                                                    $listCustomer['active'] = 'Ban';
                                                    $classActive = 'btn btn-warning';
                                                }

                                                $numList = ($page - 1) * $perPage + $listNumber + 1;

                                                echo '<tr>';
                                                echo ' <td class="td-actions">' . $numList . '</td>';
                                                echo ' <td>' . $listCustomer['name'] . '</td>';
                                                echo ' <td class="td-actions">' . $listCustomer['phone'] . '</td>';
                                                echo ' <td>' . $listCustomer['email'] . '</td>';
                                                echo ' <td  class="td-actions"><div class="' . $classActive . '">' . $listCustomer['active'] . '</div></td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<td colspan="5" class="text-center">No data displayed</td>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /widget-content -->
                        </div>
                        <div class="pull-right">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <?php
                                    if ($page > 1) {
                                        $prevPage = $page - 1;
                                        if (empty($search)) {
                                            echo '<li class="page-item">
                                                <a class="page-link btn btn-invert" href="customer_list.php?page=' . $prevPage . '">&laquo;</a>
                                            </li>';
                                        } else {
                                            echo '<li class="page-item">
                                                <a class="page-link btn btn-invert" href="customer_list.php?search=' . $search . '&page=' . $prevPage . '">&laquo;</a>
                                            </li>';
                                        }
                                    }

                                    if (empty($search)) {
                                        for ($index = 1; $index <= $maxPage; $index++) {
                                            if ($maxPage == 1 && $page = 1) {
                                                echo '<li></li>';
                                            } else { ?>
                                                <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                                    <a class="page-link btn btn-invert" href="<?php echo 'customer_list.php?page=' . $index; ?>">
                                                        <?php echo $index; ?>
                                                    </a>
                                                </li>
                                            <?php }
                                        }
                                    } else {
                                        $listAllCustomers = countTotalSearchCustomer($connection, $search, $activeDelete);

                                        $allNum = count($listAllCustomers);
                                        $maxPage = ceil($allNum / $perPage);

                                        for ($index = 1; $index <= $maxPage; $index++) {
                                            if ($maxPage == 1 && $page = 1) {
                                                echo '<li></li>';
                                            } else { ?>
                                                <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                                    <a class="page-link btn btn-invert" href="<?php echo 'customer_list.php?search=' . $search . '&page=' . $index; ?>">
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
                                                <a class="page-link btn btn-invert" href="customer_list.php?search=' . $search . '&page=' . $nextPage . '">&raquo;</a>
                                            </li>';
                                        } else {
                                            echo '<li class="page-item">
                                                <a class="page-link btn btn-invert" href="customer_list.php?page=' . $nextPage . '">&raquo;</a>
                                            </li>';
                                        }
                                    }
                                    ?>

                                </ul>
                            </nav>
                        </div>
                        <!-- /span6 -->
                    </div>
                    <!-- /row -->

                </div>
                <!-- /container -->
            </div>
            <!-- /main-inner -->
        </div>
        <br>
        <!-- /main -->

        <?php require '../../common/footer_top.php'; ?>

        <?php require '../../common/footer_bottom.php'; ?>

        <?php require '../../common/footer_lib.php'; ?>
</body>

</html>