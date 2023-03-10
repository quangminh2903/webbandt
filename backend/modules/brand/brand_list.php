<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/brand_funcs.php';

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
    $listBrands = getAllBrands($connection, $activeDelete);

    $perPage = 10;
    $allNum = count($listBrands);
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
        // get list all brands when search
        $listAllBrands = searchBrands($connection, $search, $activeDelete, $offset, $perPage);
    } else {
        // get list all brands
        $listAllBrands = getAllBrandsPagination($connection, $activeDelete, $offset, $perPage);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'List Brands';

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
                                <h3>Brands</h3>
                            </div>
                            <!-- /widget-header -->
                            <br>
                            <a class="btn btn-primary" href="./brand_add.php"><i class="icon-plus"></i>&ensp; Add new brand</a>
                            <br><br>

                            <?php
                            require_once '../../common/search.php';
                            ?>

                            <div class="widget-content">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="td-actions" style="width: 5%;">No</th>
                                            <th class="td-actions" style="width: 5%;">Image</th>
                                            <th class="td-actions" style="width: 20%;">Name</th>
                                            <th class="td-actions" style="width: 30%;">Link</th>
                                            <th class="td-actions" style="width: 10%;">Sort Order</th>
                                            <th class="td-actions" style="width: 10%;">Active</th>
                                            <th colspan="2" class="td-actions" style="width: 20%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($listAllBrands) > 0) {
                                            foreach ($listAllBrands as $listNumber => $listBrand) {
                                                if ($listBrand['active'] == '0') {
                                                    $listBrand['active'] = '<i title="Inactive" class="icon-remove"></i>';
                                                    $classActive = 'btn btn-danger';
                                                } else {
                                                    $listBrand['active'] = '<i title="Active" class="icon-ok"></i>';
                                                    $classActive = 'btn btn-success';
                                                }

                                                $numList = ($page - 1) * $perPage + $listNumber + 1;

                                                echo '<tr>';
                                                echo ' <td class="td-actions">' . $numList . '</td>';
                                                echo ' <td class="td-actions" style="width: 5%;">' . ($listBrand['image_url'] == '' ? '<div style="width: 150px;"></div>' : '<img style="width: 150px;" src="../../../common/uploads/brands/' . $listBrand['image_url'] . '">') . '</td>';
                                                echo ' <td>' . $listBrand['name'] . '</td>';
                                                echo ' <td>' . $listBrand['link'] . '</td>';
                                                echo ' <td class="td-actions">' . $listBrand['sort_order'] . '</td>';
                                                echo ' <td class="td-actions"><div class="' . $classActive . '">' . $listBrand['active'] . '</div></td>';
                                                echo ' <td class="td-actions"><a title="Edit brand" class="btn btn-warning" href="./brand_edit.php?id=' . $listBrand['id'] . '"><i class="icon-edit"></i></a></td>';
                                                echo ' <td  class="td-actions"><button title="Delete brand" data-id="' . $listBrand['id'] . '" type="button" class="btn btn-danger deletebtn"><i class="icon-trash"></i></button></td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<td colspan="8" class="td-actions">No data displayed</td>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /widget-content -->
                            <br>
                            <button class="btn btn-primary pull-right"><a class="a-button" href="brand_backup_list.php"><i class="icon-flag"></i>&ensp;Deleted brands</a></button>
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
                                    <a class="page-link btn btn-invert" href="brand_list.php?page=' . $prevPage . '">&laquo;</a>
                                </li>';
                            } else {
                                echo '<li class="page-item">
                                    <a class="page-link btn btn-invert" href="brand_list.php?search=' . $search . '&page=' . $prevPage . '">&laquo;</a>
                                </li>';
                            }
                        }

                        if (empty($search)) {
                            for ($index = 1; $index <= $maxPage; $index++) {
                                if ($maxPage == 1 && $page = 1) {
                                    echo '<li></li>';
                                } else { ?>
                                    <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                        <a class="page-link btn btn-invert" href="<?php echo 'brand_list.php?page=' . $index; ?>">
                                            <?php echo $index; ?>
                                        </a>
                                    </li>
                                <?php }
                            }
                        } else {
                            $listAllBrands = countTotalBrandSearch($connection, $search, $activeDelete);

                            $allNum = count($listAllBrands);
                            $maxPage = ceil($allNum / $perPage);

                            for ($index = 1; $index <= $maxPage; $index++) {
                                if ($maxPage == 1 && $page = 1) {
                                    echo '<li></li>';
                                } else { ?>
                                    <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                        <a class="page-link btn btn-invert" href="<?php echo 'brand_list.php?search=' . $search . '&page=' . $index; ?>">
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
                                    <a class="page-link btn btn-invert" href="brand_list.php?search=' . $search . '&page=' . $nextPage . '">&raquo;</a>
                                </li>';
                            } else {
                                echo '<li class="page-item">
                                    <a class="page-link btn btn-invert" href="brand_list.php?page=' . $nextPage . '">&raquo;</a>
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
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
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
        </form>
    </div>
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