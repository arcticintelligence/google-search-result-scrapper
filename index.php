<?php
require_once('simplehtmldom_1_5/simple_html_dom.php');

$keyword = "";
if(isset($_POST['submit'])) {
    $suffix = "Designed by myThem.es";
    $keyword = str_replace(" ", "+", $_POST['keyword'] . " " . $suffix);
}

if(!empty($keyword)) {
    $url  = 'http://www.google.com/search?hl=en&safe=active&tbo=d&site=&source=hp&q='.$keyword.'&oq=' .$keyword;
    $html = file_get_html($url);

    $linkObjs = $html->find('h3.r a');
} else {
    $linkObjs = [];
}

echo "
<!DOCTYPE html>
<html>
<head>
  <title>Another Way With WordPress</title>
  <link href='style.css' rel='stylesheet' type='text/css'>
</head>

<body>";

echo "<h1>Another Way With WordPress</h1>";
echo "
<form method='post'>
<input type='text' name='keyword' value='' placeholder='Over 60 million people...'>
<input type='submit' value='Search' name='submit'>
</form>";

if(count($linkObjs) >0) {
    echo '
<div class="qa-comments">
	<h5>'.count($linkObjs).' Results found</h5>
	<ul class="qa-comment-list">
';
} else {
    echo '
<div class="qa-comments">
	<ul class="qa-comment-list">
';
}

foreach ($linkObjs as $linkObj) {
    $title = trim($linkObj->plaintext);
    $link  = trim($linkObj->href);
    
    // if it is not a direct link but url reference found inside it, then extract
    if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) {
        $link = $matches[1];
    } else if (!preg_match('/^https?/', $link)) { // skip if it is not a valid link
        continue;    
    }
    
    //echo '<p>Title: ' . $title . '<br />';
    //echo 'Link: <a href="'.$link.'">' . $link . '</a><br></p>';
    echo '
    <li class="qa-comment qa-comment-even" id="comment-173263">
    <p><a href="'.$link.'"><span class="content_title">'.$title.'</span></a></p>

    <p><code class="codeblock codeblock-inline">'.$link.'</code></p>
    </li>
    ';
}
?>

<?php

echo "
</ul>
</div>
</body>
</html>

";

?>