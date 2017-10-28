<?php
if (empty($_SERVER['HTTPS'])) {
    header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
    exit;
}

$debug = false;
if( substr_count( $_SERVER["SCRIPT_NAME"], "sandbox") ){
    $debug = "true";
}

require_once("secret.php");

$lat = 35.69384330;
$lng = 139.70355740;
$plugin = "mode1";

session_start();

if( isset($_GET["lat"]) ){
    $lat = floatval($_GET["lat"]);
    $_SESSION["lat"] = $lat;
}else{
    if( isset($_SESSION["lat"]) ){
        $lat = floatval($_SESSION["lat"]);
    }
}

if( isset($_GET["lng"]) ){
    $lng = floatval($_GET["lng"]);
    $_SESSION["lng"] = $lng;
}else{
    if( isset($_SESSION["lng"]) ){
        $lng = floatval($_SESSION["lng"]);
    }
}

if( isset($_GET["range"]) ){
    $range = intval($_GET["range"]);
    $_SESSION["range"] = $range;
}else{
    if( isset($_SESSION["range"]) ){
        $range = intval($_SESSION["range"]);
    }
}

if( isset($_GET["div"]) ){
    $div = intval($_GET["div"]);
    $_SESSION["div"] = $div;
}else{
    if( isset($_SESSION["div"]) ){
        $range = intval($_SESSION["div"]);
    }
}

if( isset($_GET["plugin"]) ){
    $plugin = strval($_GET["plugin"]);
    $_SESSION["plugin"] = $plugin;
}else{
    if( isset($_SESSION["plugin"]) ){
        $plugin = strval($_SESSION["plugin"]);
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <title>ku-haku</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta property="fb:app_id" content="171184180129440" />
    <meta property="og:url" content="https://barcelona-prototype.com/kuhaku/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="ku-haku - 自転車と一緒に「空白」を探す" />
    <meta property="og:description" content="ぐるなびの情報を元に、まだ写真や口コミが登録されていない店を発見するサービスです。" />
    <meta property="og:image" content="https://barcelona-prototype.com/kuhaku/landing/img/og.png" />

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/starter-template.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-55877107-6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-55877107-6');
    </script>

</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">ku-haku</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">このサイトは？</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="#">ぐるなびの情報を元に</a>
                    <a class="dropdown-item" href="#">１ｋｍ程度の距離にある</a>
                    <a class="dropdown-item" href="#">まだ写真が登録されていない</a>
                    <a class="dropdown-item" href="#">お店を発見するサービスです</a>
                </div>
            </li>
        </ul>
        <form action="geocoder.php" class="form-inline my-2 my-lg-0">
            <input name="address" class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">検索</button>
        </form>
    </div>

</nav>

<div class="container">

    <div class="row">
        <div class="col-sm-6">

            <form>
                <div class="form-group">
                    <select class="form-control form-control-sm" id="plugin">
                        <option value="mode1">ぐるなび500m圏内、口コミなし、方位分散なし</option>
                        <option value="mode2">ぐるなび500m圏内、口コミなし、16方位分散</option>
                        <option value="mode3">ぐるなび1km圏内、口コミなし、16方位分散</option>
                        <option value="dummy">テスト表示（プラグイン募集中）</option>
                    </select>
                </div>
            </form>

            <div><img src="icon/0.gif"><span id="0"><img src="icon/loading.gif"></span><a class="link0">[i]</a></div>
            <div><img src="icon/1.gif"><span id="1"><img src="icon/loading.gif"></span><a class="link1">[i]</a></div>
            <div><img src="icon/2.gif"><span id="2"><img src="icon/loading.gif"></span><a class="link2">[i]</a></div>
            <div><img src="icon/3.gif"><span id="3"><img src="icon/loading.gif"></span><a class="link3">[i]</a></div>
            <div><img src="icon/4.gif"><span id="4"><img src="icon/loading.gif"></span><a class="link4">[i]</a></div>
            <div><img src="icon/5.gif"><span id="5"><img src="icon/loading.gif"></span><a class="link5">[i]</a></div>
            <div><img src="icon/6.gif"><span id="6"><img src="icon/loading.gif"></span><a class="link6">[i]</a></div>
            <div><img src="icon/7.gif"><span id="7"><img src="icon/loading.gif"></span><a class="link7">[i]</a></div>
            <div><img src="icon/8.gif"><span id="8"><img src="icon/loading.gif"></span><a class="link8">[i]</a></div>
            <div><img src="icon/9.gif"><span id="9"><img src="icon/loading.gif"></span><a class="link9">[i]</a></div>
            <?php
            if( $debug == true ){
                var_dump($_SESSION);
            }
            ?>

        </div>
        <div class="col-sm-6">
            <div id="map" style="height:400px"></div>
        </div>
    </div>

    <?php if(!$debug){echo "<!--";}?>
    <button id="up" class="btn btn-outline-primary" type="button">↑</button>
    <button id="down" class="btn btn-outline-primary" type="button">↓</button>
    <?php if(!$debug){echo "-->";}?>
    <button id="reload" class="btn btn-outline-primary" type="button">更新</button>
    <button id="location" class="btn btn-outline-primary" type="button">現在地</button>
    <a href="https://api.gnavi.co.jp/api/scope/" target="_blank">
        <img src="https://api.gnavi.co.jp/api/img/credit/api_90_35.gif" width="90" height="35" border="0" alt="グルメ情報検索サイト　ぐるなび">
    </a>


</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>
<script type="text/javascript" charset="utf-8" src="https://map.yahooapis.jp/js/V1/jsapi?appid=<?php echo $appid;?>"></script>
<script>

    var map;
    var marker = [];
    var icon = [];
    var plugin = "<?php echo $plugin;?>";

    $(function() {

        var lat = <?php echo $lat;?>;
        var lng = <?php echo $lng;?>;
        var zoom = 16;

        // Yahoo Map
        map = new Y.Map("map");
        map.addControl(new Y.CenterMarkControl({
            visibleButton: true ,
            visible      : true
        }));
        map.addControl(new Y.ZoomControl());
        map.addControl(new Y.LayerSetControl());
        map.addControl(new Y.ScaleControl());
        map.drawMap(new Y.LatLng(lat,lng), zoom, Y.LayerSetId.NORMAL);

        for(var i=0;i<10;i++){
            icon[i] = new Y.Icon('icon/'+i+'.gif');
        }

        loadSpots(false);

        $("#plugin").val("<?php echo $plugin;?>");

        $("#plugin").change(function(){
            console.log($(this).val());
            location.href = "index.php?plugin="+$(this).val();
        });

        $("#reload").click(function(){
            loadSpots(true);
        });

        $("#location").click(function(){
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (pos) {
                        map.setZoom(
                            16,
                            true,
                            new Y.LatLng(
                                pos.coords.latitude,
                                pos.coords.longitude
                        ));
                        loadSpots(true);
                    })
            }
        });

        $("#up").click(function(){
            test(0.001);
        });
        $("#down").click(function(){
            test(-0.001);
        });


    });

    function mode2url(mode){
        switch(mode){
            case "mode1":
                return "guru.php?range=2&div=16";
            case "mode2":
                return "guru.php?range=2&div=1";
            case "mode3":
                return "guru.php?range=3&div=1";
        }
    }

    function test(val){
        var latlng = map.getCenter();
        var lat = latlng.lat() + val;
        var lng = latlng.lng();
        map.panTo(new Y.LatLng(lat,lng));
        loadSpots(true);
    }

    function loadSpots(push_flag){
        var latlng = map.getCenter();
        var zoom = map.getZoom();
        var lat = latlng.lat();
        var lng = latlng.lng();
        var url = mode2url(plugin)+"&lat="+lat+"&lon="+lng;

        map.setZoom(16,true);

        for(var i=0;i<10;i++){
            map.removeFeature(marker[i]);
            $("#"+i).html('<img src="icon/loading.gif">');
        }

        $.ajax({
            url: url,
            dataType:"json",
            success: function(spots){
                console.log(spots);
                for(var i in spots){
                    console.log(spots[i]["offset"]+" "+spots[i]["name"]);
                    $("#"+i).html(spots[i]["name"]);
                    $(".link"+i).attr("href",spots[i]["url"]);
                    $(".link"+i).attr("target","_blank");
                    marker[i] = new Y.Marker(
                        new Y.LatLng(spots[i]["lat"],spots[i]["lng"]),
                        {icon: icon[i]}
                    );
                    map.addFeature(marker[i]);
                }
            }
        });
        console.log(url);
        if( push_flag == true ){
            window.history.pushState(null,null,"?lat="+lat+"&lng="+lng);
        }
    }

</script>
</body>
</html>
