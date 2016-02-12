<?php
  require_once('functions.php');
?>
<html>
<head>
  <title>Varnish URL Purge</title>
  <link rel="stylesheet" href="css/screen.css" type="text/css" />
</head>

<body>
<script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>

<script language="javascript">
$(document).ready(function()
{
    $(".defaultText").focus(function(srcc)
    {
        if ($(this).val() == $(this)[0].title)
        {
            $(this).removeClass("defaultTextActive");
            $(this).val("");
        }
    });

    $(".defaultText").blur(function()
    {
        if ($(this).val() == "")
        {
            $(this).addClass("defaultTextActive");
            $(this).val($(this)[0].title);
        }
    });

    $(".defaultText").blur();
});
</script>

<div class="container">

  <h1>Varnish Purger</h1>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" id="varnishpurge">
    <p>Enter the URL to purge</p>
    <input type="text" value="<?php isset($_POST['txtURL']) ? $_POST['txtURL'] : '' ?>" name="txtURL" class="defaultText" title="http://yourhost/some-url.html" />
  </form>

  <button type="submit" name="cmdSubmit" form="varnishpurge" value="Purge URL" class="clean-gray">Purge URL</button>

</div>
<?php
  if (isset($_POST['cmdSubmit'])) {

    $txtUrl = $_POST['txtURL'];
    $protocols = array('http://', 'https://');
    $strpos = strposa($txtUrl, $protocols);

    if ($strpos === false) {
      die("Sorry, this doesn't seem like a valid URL input.");
    }

    varnishPurge($txtUrl);

  }
?>

</body>
</html>