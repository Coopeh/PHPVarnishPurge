<?php
/**
 * varnishPurge
 *
 * @param mixed $txtUrl
 * @access  * @return void
 */
function varnishPurge ($txtUrl)
{
  // Configuration for the Varnish Server
  $varniship = '1.1.1.1';
  $varnishport = '80';
  $varnishtoken = 'blabla';

  // Step one: prepare the string, strip the http(s):// prefix
  $protocols = array('http://' => '', 'https://' => '');
  $txtUrl = strtr($txtUrl, $protocols);

  // Get the hostname/fqdn and the URL
  $hostname = substr($txtUrl, 0, strpos($txtUrl, '/'));
  $url = substr($txtUrl, strpos($txtUrl, '/'), strlen($txtUrl));

  // Open connection to Varnish and send the Purge request
  $errno = (integer) "";
  $errstr = (string) "";
  $varnish_sock = fsockopen($varniship, $varnishport, $errno, $errstr, 10);
  if (!$varnish_sock) {
          error_log("Varnish connect error: ". $errstr ."(". $errno .")");
  } else {
    // Build the request
    $cmd = "PURGE ". $url ." HTTP/1.0\r\n";
    $cmd .= "Host: ". $hostname ."\r\n";
    $cmd .= "X-Varnish-Token: ".$varnishtoken."\r\n";
    $cmd .= "Connection: Close\r\n";
    // Finish the request
    $cmd .= "\r\n";

    // Send the request
    fwrite($varnish_sock, $cmd);

    // Get the reply
    $resultsArray = array();
    while (!feof($varnish_sock)) {
      $resultsArray[] .= fgets($varnish_sock, 4096);
    }
  }
  // Close the socket
  fclose($varnish_sock);

  echo '<div class="response">';
  if (trim(strip_tags($resultsArray[23])) == 'Empty') {
    echo "<span class='error'>Couldn't find URL in cache</span>";
  }
  elseif (trim(strip_tags($resultsArray[23])) == 'Purged.') {
    echo "<span class='ok'>URL purged</span>";
  }
  else {
    echo "<span class='error'>Couldn't find URL in cache(2)<span>";
  }
  echo '</div>';
}

// Use an array in strpos
function strposa($haystack, $needle, $offset=0) {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $query) {
        if(strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
    }
    return false;
}

/**
 * varnishPurgedSimplifiedExample
 *
 * @access public
 * @return void
 */
function varnishPurgedSimplifiedExample ( )
{
    // Open the socket
    $errno = ( integer) "";
    $errstr = ( string) "";
    $varnish_sock = fsockopen( "54.155.30.126", "80", $errno, $errstr, 10);

    // Prepare the command to send
    $cmd = "PURGE /some/webpage/of/me.html HTTP/1.0\r\n";
    $cmd .= "Host: www.mydomain.be\r\n";
    $cmd .= "Connection: Close\r\n";
    $cmd .= "\r\n";

    // Send the request
    fwrite( $varnish_sock, $cmd);

    // Close the socket
    fclose( $varnish_sock);
}

?>
