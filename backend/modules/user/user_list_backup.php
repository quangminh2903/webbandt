<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/user_funcs.php';

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
    $listUsers = getAllDeletedUsers($connection, $activeDelete);
    $perPage = 10;
    $allNum = count($listUsers);
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
        // Get all list backup users when search
        $listAllUsers = searchDeletedUsers($connection, $search, $activeDelete, $offset, $perPage);
    } else {
        // Get all list backup users
        $listAllUsers = getAllDeletedUserPagination($connection, $activeDelete, $offset, $perPage);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Deleted users';

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
                            <div class="widget-header">
                                <i class="icon-user"></i>
                                <h3>Deleted users</h3>
                            </div>
                            <!-- /widget-header -->
                            <br>

                            <button class="btn btn-primary">
                                <a class="a-button" href="./user_list.php">
                                    <i class="icon-arrow-left"></i>
                                    &nbsp;
                                    Back to list users
                                </a>
                            </button>

                            <br><br>

                            <?php require_once '../../common/search.php'; ?>

                            <div class="widget-content">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="td-actions" style="width:5%;">No</th>
                                            <th class="td-actions" style="width:20%;">Username</th>
                                            <th class="td-actions" style="width:45%;">Email</th>
                                            <th class="td-actions" style="width:20%;">Active</th>
                                            <th colspan="2" class="td-actions" style="width:10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($listAllUsers) > 0) {
                                            foreach ($listAllUsers as $listNumber => $listUser) {
                                                if ($listUser['active'] == '2') {
                                                    $listUser['active'] = '<i class="icon-lock"></i>';
                                                    $classActive = 'btn btn-danger';
                                                }

                                                $numList = ($page - 1) * $perPage + $listNumber + 1;

                                                echo '<tr>';
                                                echo ' <td class="td-actions">' . $numList . '</td>';
                                                echo ' <td>' . $listUser['username'] . '</td>';
                                                echo ' <td>' . $listUser['email'] . '</td>';
                                                echo ' <td title="Locked" class="td-actions"><div class="' . $classActive . '">' . $listUser['active'] . '</div></td>';
                                                echo ' <td title="Undo user" class="td-actions">
                                                    <button data-id="' . $listUser['id'] . '" type="button" class="btn btn-info res-btn">
                                                        <i class="icon-undo"></i>
                                                    </button>
                                                </td>';

                                                echo ' <td  class="td-actions"><button title="Destroy user" data-id="' . $listUser['id'] . '" type="button" class="btn btn-danger deletebtnn"><i class="icon-trash"></button></td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<td colspan="7" class="text-center">No data displayed</td>';
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
                                        <a class="page-link btn btn-invert" href="user_list_backup.php?page=' . $prevPage . '">&laquo;</a>
                                    </li>';
                                } else {
                                    echo '<li class="page-item">
                                        <a class="page-link btn btn-invert" href="user_list_backup.php?search=' . $search . '&page=' . $prevPage . '">&laquo;</a>
                                    </li>';
                                }
                            }

                            if (empty($search)) {
                                for ($index = 1; $index <= $maxPage; $index++) {
                                    if ($maxPage == 1 && $page = 1) {
                                        echo '<li></li>';
                                    } else {  ?>
                                        <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                            <a class="page-link btn btn-invert" href="<?php echo 'user_list_backup.php?page=' . $index; ?>">
                                                <?php echo $index; ?>
                                            </a>
                                        </li>
                                    <?php }
                                }
                            } else {
                                $listAllUsers = countTotalSearchDeleted($connection, $search, $activeDelete);

                                $allNum = count($listAllUsers);
                                $maxPage = ceil($allNum / $perPage);

                                for ($index = 1; $index <= $maxPage; $index++) {
                                    if ($maxPage == 1 && $page = 1) {
                                        echo '<li></li>';
                                    } else {  ?>
                                        <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                            <a class="page-link btn btn-invert" href="<?php echo 'user_list_backup.php?search=' . $search . '&page=' . $index; ?>">
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
                                        <a class="page-link btn btn-invert" href="user_list_backup.php?search=' . $search . '&page=' . $nextPage . '">&raquo;</a>
                                    </li>';
                                } else {
                                    echo '<li class="page-item">
                                        <a class="page-link btn btn-invert" href="user_list_backup.php?page=' . $nextPage . '">&raquo;</a>
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


    <!-- BACKUP POP UP FORM -->
    <div id="restore-btn" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="exampleModelLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Backup user </h3>
        </div>
        <form action="./backup.php" method="post">
            <div class="modal-body">
                <input type="hidden" name="restore_id" id="restore_id" value="">
                <p>Do you want to backup this account ?</p>
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
            <h3 id="myModalLabel">Destroy user </h3>
        </div>
        <form action="./user_destroy.php" method="post">
            <div class="modal-body">
                <input type="hidden" name="delete_idn" id="delete_idn" value="">
                <p>Do you want to destroy this account? ?</p>
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