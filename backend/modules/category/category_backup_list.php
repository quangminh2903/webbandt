<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/category_funcs.php';

session_start();

// Check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Get data form
    $search = !empty($_GET['search']) ? trim($_GET['search']) : '';

    // Pagination
    $listCategories = getAllDeletedCategory($connection, $activeDelete);

    $perPage = 10;

    $allNum = count($listCategories);
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
        // Get all categories when search
        $listAllCategories = searchDeletedCategories($connection, $search, $activeDelete, $offset, $perPage);
    } else {
        // Get all categories
        $listAllCategories = getAllDeletedCategoryPagination($connection, $activeDelete, $offset, $perPage);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Deleted Categories';

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
                                <h3>Deleted categories</h3>
                            </div>
                            <!-- /widget-header -->
                            <br>
                            <button class="btn btn-primary"><a class="a-button" href="./category_list.php"><i class="icon-arrow-left"></i>&ensp;&nbsp;Back to list categories</a></button>
                            <br><br>

                            <?php
                            require_once '../../common/search.php';
                            ?>
                            <div class="widget-content">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="td-actions" style="width:5%;">No</th>
                                            <th class="td-actions" style="width:40%;">Name</th>
                                            <th class="td-actions" style="width:10%;">Sort Order</th>
                                            <th class="td-actions" style="width:15%;">Active</th>
                                            <th colspan="2" class="td-actions" style="width:30%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($listAllCategories) > 0) {
                                            foreach ($listAllCategories as $listNumber => $listCategory) {
                                                if ($listCategory['active'] == '2') {
                                                    $listCategory['active'] = '<i class="icon-lock"></i>';
                                                    $classActive = 'btn btn-danger';
                                                }

                                                $numList = ($page - 1) * $perPage + $listNumber + 1;

                                                echo '<tr>';

                                                echo ' <td class="td-actions">' . $numList . '</td>';
                                                echo ' <td>' . $listCategory['name'] . '</td>';
                                                echo ' <td class="td-actions">' . $listCategory['sort_order'] . '</td>';
                                                echo ' <td class="td-actions"><div class="' . $classActive . '">' . $listCategory['active'] . '</div></td>';
                                                echo ' <td  class="td-actions"><button title="Undo category" data-id="' . $listCategory['id'] . '" type="button" class="btn btn-info res-btn"><i class="icon-undo"></i></button></td>';
                                                echo ' <td  class="td-actions"><button title="Destroy category" data-id="' . $listCategory['id'] . '" type="button" class="btn btn-danger deletebtnn"><i class="icon-trash"></i></button></td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<td colspan="7" class="td-actions">No data displayed</td>';
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
                <div class="pull-right">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <?php
                            if ($page > 1) {
                                $prevPage = $page - 1;
                                if (empty($search)) {
                                    echo '<li class="page-item">
                                        <a class="page-link btn btn-invert" href="category_backup_list.php?page=' . $prevPage . '">&laquo;</a>
                                    </li>';
                                } else {
                                    echo '<li class="page-item">
                                        <a class="page-link btn btn-invert" href="category_backup_list.php?search=' . $search . '&page=' . $prevPage . '">&laquo;</a>
                                    </li>';
                                }
                            }

                            if (empty($search)) {
                                for ($index = 1; $index <= $maxPage; $index++) {
                                    if ($maxPage == 1 && $page = 1) {
                                        echo '<li></li>';
                                    } else {  ?>
                                        <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                            <a class="page-link btn btn-invert" href="<?php echo 'category_backup_list.php?page=' . $index; ?>">
                                                <?php echo $index; ?>
                                            </a>
                                        </li>
                                    <?php }
                                }
                            } else {
                                $listAllCategories = countTotalSearchDeletedCategory($connection, $search, $activeDelete);

                                $allNum = count($listAllCategories);
                                $maxPage = ceil($allNum / $perPage);

                                for ($index = 1; $index <= $maxPage; $index++) {
                                    if ($maxPage == 1 && $page = 1) {
                                        echo '<li></li>';
                                    } else {  ?>
                                        <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>"><a class="page-link btn btn-invert" href="<?php echo 'category_backup_list.php?search=' . $search . '&page=' . $index; ?>">
                                                <?php echo $index; ?>
                                            </a></li>
                            <?php }
                                }
                            }

                            $nextPage = $page + 1;
                            if ($page < $maxPage) {
                                if (!empty($search)) {
                                    echo '<li class="page-item">
                                        <a class="page-link btn btn-invert" href="category_backup_list.php?search=' . $search . '&page=' . $nextPage . '">&raquo;</a>
                                    </li>';
                                } else {
                                    echo '<li class="page-item">
                                        <a class="page-link btn btn-invert" href="category_backup_list.php?page=' . $nextPage . '">&raquo;</a>
                                    </li>';
                                }
                            }
                            ?>

                        </ul>
                    </nav>
                </div>
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
            <h3 id="myModalLabel">Backup Category </h3>
        </div>
        <form action="./category_backup.php" method="post">
            <div class="modal-body">
                <input type="hidden" name="restore_id" id="restore_id" value="">
                <p>Do you want to backup this category ?</p>
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
            <h3 id="myModalLabel">Destroy Category </h3>
        </div>
        <form action="./category_destroy.php" method="post">
            <div class="modal-body">
                <input type="hidden" name="delete_idn" id="delete_idn" value="">
                <p>Do you want to permanently destroy this category ?</p>
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

                let tr = data[4];
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

                let tr = data[5];
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