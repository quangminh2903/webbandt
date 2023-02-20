<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/product_funcs.php';

session_start();

// Check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Get form data
    $search = !empty($_GET['search']) ? trim($_GET['search']) : '';

    $listProducts = getAllProducts($connection, $activeDelete);

    // Pagination
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
        // Get list all products when search
        $listAllProducts = searchProducts($connection, $search, $activeDelete, $offset, $perPage);
    } else {
        // Get list all products
        $listAllProducts = getAllProductsPagination($connection, $activeDelete, $offset, $perPage);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'List Products';

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
                                <h3>Products</h3>
                            </div>
                            <!-- /widget-header -->
                            <br>
                            <a class="btn btn-primary" href="./product_add.php"><i class="icon-plus"></i>&ensp; Add new product</a>
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
                                                if ($listProduct['active'] == '0') {
                                                    $listProduct['active'] = '<i title="Inactive" class="icon-remove"></i>';
                                                    $classActive = 'btn btn-danger';
                                                } else {
                                                    $listProduct['active'] = '<i title="Active" class="icon-ok"></i>';
                                                    $classActive = 'btn btn-success';
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
                                                echo ' <td class="td-actions">' . ($listProduct['image'] == '' ? '<div style="width: 100px;"></div>' : '<img style="width: 100px;" src="../../../common/uploads/products/' . $listProduct['image'] . '">') . '</td>';
                                                echo ' <td class="td-actions">' . $listProduct['name'] . '</td>';
                                                echo ' <td class="td-actions">' . $listProduct['brand_name'] . '</td>';
                                                echo '<td class="td-actions">' . $listProduct['category_name'] . '</td>';
                                                echo ' <td class="td-actions"><div class="' . $classSell . '">' . $listProduct['is_best_sell'] . '</div></td>';
                                                echo ' <td class="td-actions"><div class="' . $classNew . '">' . $listProduct['is_new'] . '</div></td>';
                                                echo ' <td class="td-actions"><div class="' . $classActive . '">' . $listProduct['active'] . '</div></td>';
                                                echo ' <td class="td-actions"><a title="Detail product" class="btn btn-info" href="./product_detail.php?id=' . $listProduct['id'] . '"><i class="icon-info"></i></a></td>';
                                                echo ' <td class="td-actions"><a title="Gallery product" class="btn btn-primary" href="../gallery/gallery_list.php?product_id=' . $listProduct['id'] . '"><i class="icon-picture"></i></a></td>';
                                                echo ' <td class="td-actions"><a title="Edit product" class="btn btn-warning" href="./product_edit.php?product_id=' . $listProduct['id'] . '"><i class="icon-edit"></i></a></td>';
                                                echo ' <td class="td-actions"><button title="Delete product" data-id="' . $listProduct['id'] . '" type="button" class="btn btn-danger deletebtn"><i class="icon-trash"></i></button></td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<td colspan="12" class="td-actions">No data displayed</td>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /widget-content -->
                            <br>
                            <button class="btn btn-primary pull-right">
                                <a class="a-button" href="product_backup_list.php">
                                    <i class="icon-book"></i>
                                    &ensp; Deleted products
                                </a>
                            </button>
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
                                        <a class="page-link btn btn-invert" href="product_list.php?page=' . $prevPage . '">&laquo;</a>
                                    </li>';
                                } else {
                                    echo '<li class="page-item">
                                        <a class="page-link btn btn-invert" href="product_list.php?search=' . $search . '&page=' . $prevPage . '">&laquo;</a>
                                    </li>';
                                }
                            }

                            if (empty($search)) {
                                for ($index = 1; $index <= $maxPage; $index++) {
                                    if ($maxPage == 1 && $page = 1) {
                                        echo '<li></li>';
                                    } else { ?>
                                        <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                            <a class="page-link btn btn-invert" href="<?php echo 'product_list.php?page=' . $index; ?>">
                                                <?php echo $index; ?>
                                            </a>
                                        </li>
                                    <?php }
                                }
                            } else {
                                $listAllBrands = countTotalSearchProduct($connection, $search, $activeDelete);

                                $allNum = count($listAllBrands);
                                $maxPage = ceil($allNum / $perPage);

                                for ($index = 1; $index <= $maxPage; $index++) {
                                    if ($maxPage == 1 && $page = 1) {
                                        echo '<li></li>';
                                    } else { ?>
                                        <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                            <a class="page-link btn btn-invert" href="<?php echo 'product_list.php?search=' . $search . '&page=' . $index; ?>">
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
                                        <a class="page-link btn btn-invert" href="product_list.php?search=' . $search . '&page=' . $nextPage . '">&raquo;</a>
                                    </li>';
                                } else {
                                    echo '<li class="page-item">
                                        <a class="page-link btn btn-invert" href="product_list.php?page=' . $nextPage . '">&raquo;</a>
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

    <!-- DELETE POP UP FORM -->
    <div id="deletemodal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="exampleModelLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Delete Product</h3>
        </div>
        <form action="./product_delete.php" method="post">
            <div class="modal-body">
                <input type="hidden" name="delete_id" id="delete_id" value="<?php echo $listProduct['id']; ?>">
                <p>Do you want delete this product ?</p>
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

                let tr = data[9];
                let dom = tr['context']['innerHTML'];

                let replaced = dom.replace(/\D/g, '');

                $('#delete_id').val(replaced);
            });
        });
    </script>

</body>

</html>