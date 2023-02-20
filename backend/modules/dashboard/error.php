<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = '404 - Bootstrap Admin Template';

require '../../common/header_lib.php';
?>

<?php require '../../../common/constant.php'; ?>

<body>

<?php require '../../common/navbar.php'; ?>

<div class="container">

	<div class="row">

		<div class="span12">

			<div class="error-container">
				<h1>404</h1>

				<h2>Who! bad trip man. No more pixesl for you.</h2>

				<div class="error-details">
					Sorry, an error has occured! Why not try going back to the <a href="index.php">home page</a> or perhaps try following!

				</div> <!-- /error-details -->

				<div class="error-actions">
					<a href="index.php" class="btn btn-large btn-primary">
						<i class="icon-chevron-left"></i>
						&nbsp;
						Back to Dashboard
					</a>



				</div> <!-- /error-actions -->

			</div> <!-- /error-container -->

		</div> <!-- /span12 -->

	</div> <!-- /row -->

</div> <!-- /container -->


<script src="../../common/js/jquery-1.7.2.min.js"></script>
<script src="../../common/js/bootstrap.js"></script>

</body>

</html>
