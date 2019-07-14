<?php global $home; global $bodyclass; ?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" class="no-js">
<head>


<?php function sanitize_output($buffer) {
  // minify html
  require_once('minify/html.php');
  $buffer = Minify_HTML::minify($buffer);
  return $buffer;
}
ob_start('sanitize_output'); ?>


<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1" />

<!-- remove IE and Windows Phone highlight -->
<meta name="msapplication-tap-highlight" content="no">

<meta property="og:locale" content="en_US" />
<meta property="og:title" content="erica dreisbach | freelance web developer" />
<meta property="og:image" content="<?php if(isset($ogimg)) { echo $ogimg; } else { echo ' https://s3.amazonaws.com/darkblack-papa/erica-dreisbach-freelance-chicago-web-developer.jpg'; } ?>" />
<meta property="og:url" content="https://ericadreisbach.com" />
<meta property="og:site_name" content="erica dreisbach | freelance web developer" />
<meta property="og:description" content="<?php if(isset($metadescription)) { echo $metadescription; } ?>" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="<?php if(isset($page)) { echo $page . ' | '; } ?>erica dreisbach | freelance web developer" />
<meta name="twitter:description" content="<?php if(isset($metadescription)) { echo $metadescription; } ?>" />
<meta name="twitter:image" content="<?php if(isset($ogimg)) { echo $ogimg; } else { echo ' https://s3.amazonaws.com/darkblack-papa/erica-dreisbach-freelance-chicago-web-developer.jpg'; } ?>" />

<meta name="description" content="<?php if(isset($metadescription)) { echo $metadescription; } ?>" />


<title><?php if(isset($page)) { echo $page . ' | '; } else { ;} ?>erica dreisbach | freelance web developer </title>



<!-- favicons -->
<?php include('favicons.php'); ?>


 <!--[if lt IE 9]>
   <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
   <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
 <![endif]-->


<?php if(isset($page)) : ?>
 <link rel="stylesheet" type="text/css" href="../css/style.css?v=1.1.3" />
<?php endif; ?>


<script>
if (document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image", "1.1")) { }
else { document.documentElement.className = "nosvg"; }
</script>



</head>



<body class="<?php if(isset($bodyclass)) { echo $bodyclass; }?>" <?php if (!isset($page)) { echo 'data-spy="scroll" data-target=".navbar" data-offset="50"'; } ?> style="margin: 0;" >


<?php if(isset($page)) { include('nav.php'); } ?>
