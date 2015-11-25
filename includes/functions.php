<?php
function slug($text){
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    // trim
    $text = trim($text, '-');
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // lowercase
    $text = strtolower($text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    if (empty($text))
    {
        return 'n-a';
    }
    return $text;
}

/**
 * This function just checks referring sites/pages aka the visitors last page before coming to ours
 *
 * @param $current string URL of current page (the calling link)
 * @param $title string the page's title for ease of viewing
 * @return void
 */
function whereFrom($current, $title) {
    global $db;
    $referer = '';

    //check that both are string before we go ahead
    if(!is_string($current) || !is_string($title)) {
        return;
    }

    //check referrer
    if(!isset($_SERVER['HTTP_REFERER']) || !is_string($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == '') {
        $referer = 'Unknown - Referer not set';
    } else {
        $referer = $_SERVER['HTTP_REFERER'];
    }

    //prep query
    $whe = $db->prepare("INSERT INTO blog_referer (page_title, page_url, referer_url, date_added) VALUES (:title, :cur, :referer, :dates)");
    $whe->execute(array(
        ':title' => $title,
        ':cur' => $current,
        ':referer' => $referer,
        ':dates' => date("Y-m-d H:i:s")
    ));

}
?>