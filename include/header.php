<?
$page=$_REQUEST[page];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>CMS</title>
<link href="styles/formating.css" rel="stylesheet" type="text/css" />
<link href="styles/cms.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,300,700' rel='stylesheet' type='text/css'>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="scripts/functions.js"></script>
</head>
<body>

    <div class="wrapper">
        <div class="navigation">
            <?
            $i=0;
            while($i<count($menu)) {
                $title = $menu[$i][title];
                $one = $menu[$i][one];
                $many = $menu[$i][many];
                $url = $menu[$i][url];
                
                $string = "<a href='";
                if(empty($url)) { $string .= "index.php?page=$many "; }
                else { $string .= $url; }
                $string .= "'";
                if( ($page==$menu[$i]['one'] || $page==$menu[$i]['many']) && !empty($page)) { $string .= "class='selected'"; }
                $string .= ">{$menu[$i]['title']}</a> ";
                echo $string;
                $i++;	
            }
            ?>
            <a href='' class='bt_settings'><img src='images/bt_settings.png'/></a>
        </div>