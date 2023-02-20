<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/product_funcs.php';
require_once '../../funcs/gallery_funcs.php';

session_start();

// check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

// get id product_id
if (empty($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    header('location: ../product/product_list.php');
    exit();
}
$productId = !empty($_GET['product_id']) ? trim($_GET['product_id']) : '';

$infoProduct = getInfoProduct($connection, $productId);

if (empty($infoProduct)) {
    header('location: ../product/product_list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $listGalleryBackup = getAllBackupGalleryById($connection, $productId, $activeDelete);

    // pagination
    $perPage = 10;
    $allNum = count($listGalleryBackup);
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

    // get list all backup gallery by id
    $listAllBackupGalleryById = getAllBackupGalleryPagination($connection, $productId, $activeDelete, $offset, $perPage);
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'List Gallery';

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
                            <div class="widget-header"> <i class="icon-picture"></i>
                                <h3>Deleted galleries product's <b style="color:#F30">
                                        <?php echo $infoProduct['name']; ?></b> </h3>
                            </div>
                            </h3>
                        </div>
                        <!-- /widget-header -->
                        <br>
                        <a class="btn btn-primary" href="./gallery_list.php?product_id=<?php echo $productId; ?>">
                            <i class="icon-arrow-left"></i>
                            &nbsp;
                            Back to list galleries of product's
                            <b>
                                <?php echo $infoProduct['name']; ?>
                            </b>
                        </a>

                        <br><br>

                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center;" style="width: 5%;">No</th>
                                    <th style="text-align: center;">Image</th>
                                    <th style="text-align: center;" style="width: 10%;">Sort Order</th>
                                    <th style="text-align: center;" style="width: 5%;">Active</th>
                                    <th colspan="2" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($listAllBackupGalleryById) > 0) {
                                    foreach ($listAllBackupGalleryById as $listNumber => $listGallery) {
                                        if ($listGallery['active'] == '2') {
                                            $listGallery['active'] = '<i title="Locked" class="icon-lock"></i>';
                                            $classActive = 'btn btn-danger';
                                        }

                                        $numList = ($page - 1) * $perPage + $listNumber + 1;

                                        echo '<tr>';
                                        echo ' <td style="text-align: center;">' . $numList . '</td>';
                                        echo ' <td style="text-align: center;">' . ($listGallery['image_url'] == '' ? '<div style="width: 70px; height: 70px"></div>' : '<img style="width: 100px;" src="../../../common/uploads/galleries/' . $listGallery['image_url'] . '">') . '</td>';
                                        echo ' <td style="text-align: center;">' . $listGallery['sort_order'] . '</td>';
                                        echo ' <td style="text-align: center;"><div class="' . $classActive . '">' . $listGallery['active'] . '</div></td>';
                                        echo '<td  style="text-align: center;"><button title="Undo gallery" data-id="' . $listGallery['id'] . '" type="button" class="btn btn-info res-btn"><i class="icon-undo"></i></button></td>';
                                        echo ' <td style="text-align: center;"><button title="Destroy gallery" data-id="' . $listGallery['id'] . '" type="button" class="btn btn-danger deletebtnn"><i class="icon-trash"></i></button></td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<td colspan="6" style="text-align: center;">No data displayed</td>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <!-- /widget-content -->

                    </div>
                </div>
                <!-- /span6 -->
            </div>
            <!-- /row -->

            <!-- PAGINATION -->
            <br>
            <div class="pull-right" style="margin-right:100px;">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?php
                        if ($page > 1) {
                            $prevPage = $page - 1;

                            echo '<li class="page-item">
                                <a class="page-link btn btn-invert" href="gallery_list.php?product_id=' . $productId . '&page=' . $prevPage . '">&laquo;</a>
                            </li>';
                        }

                        for ($index = 1; $index <= $maxPage; $index++) {
                            if ($maxPage == 1 && $page = 1) {
                                echo '<li></li>';
                            } else { ?>
                                <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                                    <a class="page-link btn btn-invert" href="<?php echo 'gallery_backup_list.php?product_id=' . $productId . '&page=' . $index; ?>">
                                        <?php echo $index; ?>
                                    </a>
                                </li>
                        <?php }
                        }

                        $nextPage = $page + 1;
                        if ($page < $maxPage) {
                            echo '<li class="page-item">
                                <a class="page-link btn btn-invert" href="gallery_backup_list.php?product_id=' . $productId . '&page=' . $nextPage . '">&raquo;</a>
                            </li>';
                        }
                        ?>

                    </ul>
                </nav>
            </div>
            <br>
            <br>
            <br>
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
            <h3 id="myModalLabel">Backup Gallery </h3>
        </div>
        <form action="./gallery_backup.php?product_id=<?php echo $productId; ?>" method="post">
            <div class="modal-body">
                <input type="hidden" name="restore_id" id="restore_id" value="">
                <p>Do you want to backup this image ?</p>
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
            <h3 id="myModalLabel">Destroy Gallery </h3>
        </div>
        <form action="./gallery_destroy.php?product_id=<?php echo $productId; ?>" method="post">
            <div class="modal-body">
                <input type="hidden" name="delete_idn" id="delete_idn" value="">
                <p>Do you want to permanently destroy this image ?</p>
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

                let tr = data[5];
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