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

// get info product by productId
$infoProduct = getInfoProduct($connection, $productId);

if (empty($infoProduct)) {
    header('location: ../product/product_list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $listGallery = getAllGalleryById($connection, $productId, $activeDelete);

    // pagination
    $perPage = 10;
    $allNum = count($listGallery);
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

    // get all gallery by productId
    $listAllGalleryById = getAllGalleryPagination($connection, $productId, $activeDelete, $offset, $perPage);
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
                                <h3>Galleries of product's <b style="color:#F30">
                                        <?php echo $infoProduct['name']; ?></b> </h3>
                            </div>
                            </h3>
                        </div>
                        <!-- /widget-header -->
                        <a class="btn btn-primary" href="./gallery_add.php?product_id=<?php echo $productId; ?>">
                            <i class="icon-plus"></i>
                            &ensp;
                            Add new gallery
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
                                if (count($listAllGalleryById) > 0) {
                                    foreach ($listAllGalleryById as $listNumber => $listGallery) {
                                        if ($listGallery['active'] == '0') {
                                            $listGallery['active'] = '<i title="Inactive" class="icon-remove"></i>';
                                            $classActive = 'btn btn-danger';
                                        } else {
                                            $listGallery['active'] = '<i title="Active" class="icon-ok"></i>';
                                            $classActive = 'btn btn-success';
                                        }

                                        $numList = ($page - 1) * $perPage + $listNumber + 1;

                                        echo '<tr>';

                                        echo ' <td style="text-align: center;">' . $numList . '</td>';
                                        echo ' <td style="text-align: center;">' . ($listGallery['image_url'] == '' ? '<div style="width: 70px; height: 70px"></div>' : '<img style="width: 100px; height: 70px" src="../../../common/uploads/galleries/' . $listGallery['image_url'] . '">') . '</td>';
                                        echo ' <td style="text-align: center;">' . $listGallery['sort_order'] . '</td>';
                                        echo ' <td style="text-align: center;"><div class="' . $classActive . '">' . $listGallery['active'] . '</div></td>';
                                        echo ' <td style="text-align: center;"><a title="Edit gallery" class="btn btn-warning" href="./gallery_edit.php?product_id=' . $productId . '&id=' . $listGallery['id'] . '"><i class="icon-edit"></i></a></td>';
                                        echo ' <td  style="text-align: center;"><button title="Delete gallery" data-id="' . $listGallery['id'] . '" type="button" class="btn btn-danger deletebtn"><i class="icon-trash"></i></button></td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<td colspan="6" style="text-align: center;">No data displayed</td>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <!-- /widget-content -->
                        <br>
                        <button class="btn btn-primary pull-right">
                            <a class="a-button" href="gallery_backup_list.php?product_id=<?php echo $productId; ?>">
                                <i class="icon-picture"></i>
                                &ensp;
                                Deleted galleries
                            </a>
                        </button>
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
                                    <a class="page-link btn btn-invert" href="<?php echo 'gallery_list.php?product_id=' . $productId . '&page=' . $index; ?>">
                                        <?php echo $index; ?>
                                    </a>
                                </li>
                        <?php }
                        }

                        $nextPage = $page + 1;
                        if ($page < $maxPage) {
                            echo '<li class="page-item">
                                <a class="page-link btn btn-invert" href="gallery_list.php?product_id=' . $productId . '&page=' . $nextPage . '">&raquo;</a>
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

    <!-- DELETE POP UP FORM -->
    <div id="deletemodal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="exampleModelLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Delete image </h3>
        </div>
        <form action="./gallery_delete.php?product_id=<?php echo $productId; ?>" method="post">
            <div class="modal-body">
                <input type="hidden" name="delete_id" id="delete_id" value="<?php echo $listGallery['id']; ?>">
                <p>Do you want delete this image ?</p>
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

                let tr = data[5];
                let dom = tr['context']['innerHTML'];

                let replaced = dom.replace(/\D/g, '');

                $('#delete_id').val(replaced);
            });
        });
    </script>

</body>

</html>