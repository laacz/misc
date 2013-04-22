<?
header('Content-Type: text/html; charset=utf-8');

if (isset($_GET['src'])) {
	die(highlight_file(__FILE__, true));
}
error_reporting(E_ALL);

$dir = isset($_GET['dir']) ? $_GET['dir'] : (isset($_POST['dir']) ? $_POST['dir'] : 1);
$c = isset($_POST['c']) ? $_POST['c'] : false;
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title>Latviešu translita konvertors</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
    
	#wrapper {
		width: 80%;
		margin: 0 auto;
		text-align: left;
	}
	
	body {
		text-align: center;
		font-family: tahoma, sans-serif;
		font-size: 90%;
    }
    
	h1,
	h2 {
		font-weight: normal;
		color: green;
		border-bottom: 1px solid green;
	}
	
	h2 {
		color: maroon;
		border-color: maroon;
		border-bottom-style: dashed;
	}
	
    .t {
        width: 100%; 
        min-height: 150px; 
        font-family: "courier new", monospace;
		font-size: 95%;
        color: #333;
        border-style: solid;
        border-width: 1px;
        border-color: #000 #ccc #ccc #000 ;
        background-color: #f0f0f0;
    }
	
	.t:focus {
		background-color: #ffc;
	}
	
	div.t {
		width: auto;	
	}
	
	table td {
		border-color: #ccf;
		border-width: 1px;
		border-style: solid;
		padding: 2px;
	}
	
	#footer {
		border-top: 1px solid #666;
		padding-top: .5em;
		margin-top: .5em;
	}
	
	p.submit {
		text-align: center;
	}
	
    </style>
</head>

<body>

	<div id="wrapper">

<script type="text/javascript"><!--
google_ad_client = "ca-pub-1458323184003510";
/* dev.laacz.lv projects - large */
google_ad_slot = "4627569637";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

        <h1>Konvertējam latviešu tekstus uz/no translita.</h1>
	
<!--
<p>Implementācija nav pilnīga, protams. Taču, manām vajadzībām pilnībā pietiek (lai pēc tam izbrauktu cauri ar spellčekeru un visu salabotu, kas palicis neizlabots).</p>
-->
	
	<h2>Ievadi tekstu</h2>
	
	<form action="" method="post">
	<dl>
		<dt><label for="c">Teksts:</label> </dt>
		<dd>
			<textarea id="c" name="c" class="t"><?=htmlspecialchars($c)?></textarea>
		</dd>
		
		<dt>Konvertācija: </dt>
		<dd><label for="dir_1">No translita uz parasto:</label> <input type="radio" id="dir_1" name="dir" value="1" <?=$dir == 1 ? 'checked="checked"' : ''?> /> <label for="dir_1">('aa' uz 'ā')</label></dd>
		<dd><label for="dir_2">No parastā uz translitu:</label> <input type="radio" id="dir_2" name="dir" value="2" <?=$dir == 2 ? 'checked="checked"' : ''?> /> <label for="dir_2">('ā' uz 'aa')</label></dd>
		
	</dl>
	
	<p class="submit"><input type="submit" value="Spied šo pogu, lai viss notiktu &raquo;&raquo;" /></p>
	
	</form>
	
	<?
	
	mb_internal_encoding('utf-8');
	
	function strup($str) {
		$str = strtoupper($str);
		$str = str_replace('ŗ', 'Ŗ', $str);
		$str = mb_strtoupper($str);
		return($str);
	}
	
	function strlow($str) {
		$str = strtolower($str);
		$str = str_replace('Ŗ', 'ŗ', $str);
		$str = mb_strtolower($str);
		return($str);
	}
	
	$f = Array('aa', 'ch', 'ee', 'gj', 'ii', 'kj', 'lj', 'nj', 'rj', 'sh', 'uu', 'zh', 'oo');
	$t = Array('ā',  'č',  'ē',  'ģ',  'ī',  'ķ',  'ļ',  'ņ',  'ŗ',  'š',  'ū',  'ž',  'ō');


	for ($i = 0; $i < count($f); $i++) {
		$tolv[$f[$i]] = $t[$i];
		if (!isset($fromlv[$t[$i]]) || trim($fromlv[$t[$i]]) == '')
			$fromlv[$t[$i]] = $f[$i];
	}
	
	
	function tolv($q) {
		global $f, $t, $tolv, $fromlv;
		$c = $q;
		$c = str_replace('eee', 'eē', $c);
		$c = str_replace('Eee', 'Eē', $c);
		$c = str_replace('eEe', 'eĒ', $c);
		$c = str_replace('EEe', 'EĒ', $c);
		$c = str_replace('EEE', 'EĒ', $c);
		for ($i = 0; $i < strlen($c); $i++) {
			$ch = substr($c, $i, 2);
			$chs = strlow($ch);
			$c1 = substr($ch, 0, 1);
			$c2 = substr($ch, 1, 2);
			if (in_array($chs, $f)) {
				$tmp = $c1 == strup($c1) ? strup($tolv[$chs]) : $tolv[$chs];
				$c = substr($c, 0, $i) . $tmp . substr($c, $i+2);
			}
		}
		$c = str_replace('iē', 'iee', $c);
		return ($c);
	}
	
	function fromlv($q) {
		global $f, $t, $tolv, $fromlv;
		$c = $q;
		for ($i = 0; $i < mb_strlen($c); $i++) {
			$ch = mb_substr($c, $i, 1);
			$chs = mb_strtolower($ch, 'UTF-8');
			if (in_array($chs, $t)) {
				$ch1 = mb_substr($fromlv[$chs], 0, 1);
				$ch2 = mb_substr($fromlv[$chs], 1, 2);
				if ($ch != $chs) {
					$c1 = mb_substr($c, $i+1, 1);
					if ($c1 == strup($c1)) {
						$tmp = strup($ch1.$ch2);
					} else {
						$tmp = strup($ch1).$ch2;
					}
				} else {
					$tmp = $fromlv[$chs];
				}
				$c = mb_substr($c, 0, $i) . $tmp . mb_substr($c, $i+1);
			}
		}
		return ($c);
	}
	
	if (strlen($c) && $dir) {
		$o = $dir == 1 ? tolv($c) : fromlv($c);
		?>
		<h2>Rezultāts</h2>
		<div style="margin-left: 3em; " class="t">
			<?=nl2br(htmlspecialchars($o);?>
		</div>
	<?}?>
	
	<h2>Transliterācijas tabula</h2>
	
	<table>
	<tr><th>Transliterācija:</th><?for ($i = 0; $i < count($f); $i++) {?><td><?=strup($f[$i])?></td><?}?></tr>
	<tr><th>Ierastā rakstība:</th><?for ($i = 0; $i < count($f); $i++) {?><td><?=strup($t[$i])?></td><?}?></tr>
	</table>
	
	<p id="footer">Autors - <a href="http://laacz.lv/">Kaspars Foigts</a>. Kaut kad 2002. gadā, vai?</p>

	</div>
	
</body>
</html>
