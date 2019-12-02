<?php
$localIP = getHostByName(php_uname('n'));
?>
<html>

<head>
    <title>HTML with PHP</title>
    <style type="text/css">
        body {
            margin: 0px;
        }

        .iframe-16-9 {
            position: relative;
            padding-bottom: 56.25%;
            padding-top: 35px;
            height: 0;
            overflow: hidden;
        }

        .iframe-16-9 iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    <div class="iframe-16-9">
        <iframe src="http://<?php echo $_SERVER['SERVER_ADDR'] ?>:8082/?action=stream" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" id="frmVideo"></iframe>
    </div>
</body>

</html>