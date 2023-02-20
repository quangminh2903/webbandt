<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/product_funcs.php';

session_start();

if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Get data search
    $search = !empty($_GET['search']) ? trim($_GET['search']) : '';

    // Pagination
    $listProducts = getAllBackupProduct($connection, $activeDelete);

    $perPage = 10;

    $allNum = count($listProducts);
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
        // Get list all backup product when search
        $listAllProducts = searchBackupProduct($connection, $search, $activeDelete, $offset, $perPage);
    } else {
        // Get list all backup product
        $listAllProducts = getAllBackupProductPagination($connection, $activeDelete, $offset, $perPage);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Deleted Products';

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
                            <div class="widget-header"> <i class="icon-book"></i>
                                <h3>Deleted products</h3>
                            </div>
                            <!-- /widget-header -->
                            <br>
                            <a class="btn btn-primary" href="./product_list.php">
                                <i class="icon-arrow-left"></i>
                                &nbsp;
                                Back to list products
                            </a>
                            <br><br>

                            <?php
                            require_once '../../common/search.php';
                            ?>

                            <div class="widget-content">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="td-actions" style="width: 5%;">No</th>
                                            <th class="td-actions" style="width: 15%;">Image</th>
                                            <th class="td-actions" style="width: 20%;">Name</th>
                                            <th class="td-actions" style="width: 10%;">Brand</th>
                                            <th class="td-actions" style="width: 15%;">Category</th>
                                            <th class="td-actions" style="width: 10%;">Best sell</th>
                                            <th class="td-actions" style="width: 10%;">New</th>
                                            <th class="td-actions" style="width: 5%;">Active</th>
                                            <th colspan="4" class="td-actions" style="width: 10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($listAllProducts) > 0) {
                                            foreach ($listAllProducts as $listNumber => $listProduct) {
                                                if ($listProduct['active'] == '2') {
                                                    $listProduct['active'] = '<i title="Locked" class="icon-lock"></i>';
                                                    $classActive = 'btn btn-danger';
                                                }

                                                if ($listProduct['is_best_sell'] == '0') {
                                                    $listProduct['is_best_sell'] = 'No';
                                                    $classSell = 'btn btn-danger';
                                                } else {
                                                    $listProduct['is_best_sell'] = 'Yes';
                                                    $classSell = 'btn btn-success';
                                                }

                                                if ($listProduct['is_new'] == '0') {
                                                    $listProduct['is_new'] = 'No';
                                                    $classNew = 'btn btn-danger';
                                                } else {
                                                    $listProduct['is_new'] = 'Yes';
                                                    $classNew = 'btn btn-success';
                                                }

                                                $numList = ($page - 1) * $perPage + $listNumber + 1;

                                                echo '<tr>';

                                                echo ' <td class="td-actions">' . $numList . '</td>';
                                                echo ' <td class="td-actions">' . ($listProduct['image'] == '' ? '<div style="width: 150px;"></div>' : '<img style="width: 150px;" src="../../../common/uploads/products/' . $listProduct['image'] . '">') . '</td>';
                                                echo ' <td class="td-actions">' . $listProduct['name'] . '</td>';
                                                echo ' <td class="td-actions">' . $listProduct['brand_name'] . '</td>';
                                                echo ' <td class="td-actions">' . $listProduct['category_name'] . '</td>';
                                                echo ' <td class="td-actions"><div class="' . $classSell . '">' . $listProduct['is_best_sell'] . '</div></td>';
                                                echo ' <td class="td-actions"><div class="' . $classNew . '">' . $listProduct['is_new'] . '</div></td>';
                                                echo ' <td class="td-actions"><div class="' . $classActive . '">' . $listProduct['active'] . '</div></td>';
                                                echo ' <td  class="td-actions"><button title="Undo product" data-id="' . $listProduct['id'] . '" type="button" class="btn btn-info res-btn"><i class="icon-undo"></i></button></td>';
                                                echo ' <td  class="td-actions"><button title="Destroy product" data-id="' . $listProduct['id'] . '" type="button" class="btn btn-danger deletebtnn"><i class="icon-trash"></i></button></td>';
                                            }
                                        } else {
                                            echo '<td colspan="10" class="td-actions">No data displayed</td>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /widget-content -->
                            <br>
                        </div>
                    </div>
                    <!-- /span6 -->
                </div>
                <!-- /row -->

                <!-- PAGINATION -->
                <div class="pull-right">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <?php
                            if ($page > 1) {
                                $prevPage = $page - 1;
                                if (empty($search)) {
                                    echo '<li class="page-item">
                                        <a class="page-link btn btn-invert" href="product_backup_list.php?page=' . $prevPage . '">&laquo;</a>
                                    </li>';
                                } else {
                                    echo '<li class="page-item">
                                        <a class="page-link btn btn-invert" href="product_backup_list.php?search=' . $search . '&page=' . $prevPage . '">&laquo;</a>
                                    </li>';
                                }
                            }

                            if (empty($search)) {
                                for ($index = 1; $index <= $maxPage; $index++) {
                                    if ($maxPage == 1 && $page = 1) {
                                        echo '<li></li>';
                                    } else { ?>
                                        <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                            <a class="page-link btn btn-invert" href="<?php echo 'product_backup_list.php?page=' . $index; ?>">
                                                <?php echo $index; ?>
                                            </a>
                                        </li>
                                    <?php }
                                }
                            } else {
                                $listAllBrands = countTotalSearchBackupProduct($connection, $search, $activeDelete);

                                $allNum = count($listAllBrands);
                                $maxPage = ceil($allNum / $perPage);

                                for ($index = 1; $index <= $maxPage; $index++) {
                                    if ($maxPage == 1 && $page = 1) {
                                        echo '<li></li>';
                                    } else { ?>
                                        <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                            <a class="page-link btn btn-invert" href="<?php echo 'product_backup_list.php?search=' . $search . '&page=' . $index; ?>">
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
                                        a class="page-link btn btn-invert" href="product_backup_list.php?search=' . $search . '&page=' . $nextPage . '">&raquo;</a>
                                    </li>';
                                } else {
                                    echo '<li class="page-item">
                                        <a class="page-link btn btn-invert" href="product_backup_list.php?page=' . $nextPage . '">&raquo;</a>
                                    </li>';
                                }
                            }
                            ?>

                        </ul>
                    </nav>
                </div>
                <!-- END PAGINATION -->

            </div>
            <!-- /container -->
        </div>
        <!-- /main-inner -->
    </div>
    <!-- /main -->

    <!-- RESTORE POP UP FORM -->
    <div id="restore-btn" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="exampleModelLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Backup Product </h3>
        </div>
        <form action="./product_backup.php" method="post">
            <div class="modal-body">
                <input type="hidden" name="restore_id" id="restore_id" value="">
                <p>Do you want to backup this product ?</p>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-info">Backup</button>
            </div>
        </form>
    </div>

    <!-- END RESTORE POP UP FORM -->

    <!-- DELETE POP UP FORM -->
    <div id="deletemodaln" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="exampleModelLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Destroy Product </h3>
        </div>
        <form action="./product_destroy.php" method="post">
            <div class="modal-body">
                <input type="hidden" name="delete_idn" id="delete_idn" value="">
                <p>Do you want to permanently destroy this product ?</p>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-danger">Destroy</button>
            </div>
        </form>
    </div>

    <!-- END DELETE POP UP FORM -->

    <?php require '../../common/footer_top.php'; ?>

    <?php require '../../common/footer_bottom.php'; ?>

    <?php require '../../common/footer_lib.php'; ?>

    <script>
        $(document).ready(function() {
            $('.res-btn').on('click', function() {
                $('#restore-btn').modal('show');

                $tr = $(this).closest('tr');

                let data = $tr.children("td").map(function() {
                    return $(this);
                });

                let tr = data[9];
                let dom = tr['context']['innerHTML'];
                console.log(dom);

                let replaced = dom.replace(/\D/g, '');
                console.log(replaced);

                $('#restore_id').val(replaced);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.deletebtnn').on('click', function() {
                $('#deletemodaln').modal('show');

                $tr = $(this).closest('tr');

                let data = $tr.children("td").map(function() {
                    return $(this);
                });

                let tr = data[9];
                let dom = tr['context']['innerHTML'];
                console.log(dom);

                let replaced = dom.replace(/\D/g, '');
                console.log(replaced);

                $('#delete_idn').val(replaced);
            });
        });
    </script>

</body>

</html>