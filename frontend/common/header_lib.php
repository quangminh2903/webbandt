<?php
  $currentUrl = getCurrentUrl();

  $authUrl = ($currentUrl == 'login.php' || $currentUrl == 'register.php' || $currentUrl == 'forgot_password.php' || $currentUrl == 'reset_password.php' || $currentUrl == 'change_password.php' || $currentUrl == 'profile.php');
?>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $pageTitle; ?></title>

  <!-- Google Fonts -->
  <link href="http://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,700,600" rel="stylesheet" type="text/css" />
  <link href="http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300" rel="stylesheet" type="text/css" />
  <link href="http://fonts.googleapis.com/css?family=Raleway:400,100" rel="stylesheet" type="text/css" />

  <?php if ($authUrl) { ?>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../theme/css/bootstrap.min.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../theme/css/font-awesome.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../theme/css/owl.carousel.css" />
    <link rel="stylesheet" href="../theme/style.css" />
    <link rel="stylesheet" href="../theme/css/auth.css" />
    <link rel="stylesheet" href="../theme/css/responsive.css" />
  <?php } else { ?>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="theme/css/bootstrap.min.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="theme/css/font-awesome.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="theme/css/owl.carousel.css" />
    <link rel="stylesheet" href="theme/style.css" />
    <link rel="stylesheet" href="theme/css/responsive.css" />
  <?php } ?>

  <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>