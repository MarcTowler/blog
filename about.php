<?php
require_once('includes/config.php');
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>
        <?php echo SITENAME; ?> - About Me
    </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="<?php echo DESCRIPTION ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/main.css">
    <link rel="alternate" href="rss.php" title="My RSS feed" type="application/rss+xml" />
    <script src="js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body>
<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div id="wrapper">
    <h1>
        <?php echo SITENAME; ?>
    </h1>
    <div id="nav">
        <?php require_once('nav.php'); ?>
    </div>

    <div id="main">
        <h2>About <?php echo SITENAME; ?></h2>

        <h3>The Blog</h3>
        <p>The blog <?php echo SITENAME; ?> was brought to life to help me store my knowledge and improve my writing skills.
        Along the way I have found that people have been able to learn from the knowledge placed here so the writing has
        changed to be more of a community knowledge bank where anyone should be able to pick up little tips and tricks
        as well as hopefully taking a look at some of the recommendations I make from book reviews.</p>

        <h3>The Author</h3>
        <p>
        Currently spent the past 5 years working for o2, a mobile phone retailer in the UK. I spend my spare time studying
        for my degree in Computer Science through <a href="http://www.open.ac.uk">The Open University</a> of which I am 3 years
        into now. I also work as a freelance developer and help to create websites with my friend Marquis as part of
        <a href="https://www.designdeveloprealize.com">DDR</a>. I am an avid gamer with Metal Gear Solid and Final Fantasy
        being my main two franchises that I play and only on PC or PS4!</p>
        <p>
            <img src="https://marctowler.co.uk/img/ambassador.png"/>
            On top of all of this, I recently requested and was accepted as an ambassador for <a href="http://sitepoint.com">SitePoint</a>
            which has been an excellent experience so far and the return that I have been given is the ability to improve my own
            knowledge at my own pace for a reasonable price.
        </p>

    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>

<!-- Piwik -->
<script type="text/javascript">
    var _paq = _paq || [];
    _paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
    _paq.push(["setCookieDomain", "*.marctowler.co.uk"]);
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function() {
        var u=(("https:" == document.location.protocol) ? "https" : "http") + "://marctowler.co.uk/piwik/";
        _paq.push(['setTrackerUrl', u+'piwik.php']);
        _paq.push(['setSiteId', 1]);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
        g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
    })();
</script>
<noscript><p><img src="https://marctowler.co.uk/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-37729517-1', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>
