<?
header('Content-Type: text/html; charset=utf-8');

mb_internal_encoding("UTF-8");

$patsk = 'āēīōūaeiou';
$zilbe = '';
$txt = isset($_POST['txt']) ? $_POST['txt'] : false;

$out = '';

if ($txt) {
    for ($i = 0; $i < mb_strlen($txt)+1; $i++) {
        $char = mb_substr($txt, $i, 1);
        if (mb_strpos($patsk, mb_convert_case($char, MB_CASE_LOWER)) !== false) {
            $zilbe .= $char;
        } else {
            if (mb_strlen($zilbe)) {
                $out .= $zilbe . 'p' . $zilbe;
                $zilbe = '';
            }
            $out .= $char;
        }
    }
}

?>


<?='<?xml version="1.0" encoding="UTF-8"?>'?>
<!doctype html>
<html>
<head>
    <title>Pupiņvaloda</title>
    <meta charset="utf-8"/>
</head>
<body>
    <p><?=nl2br(htmlspecialchars($out))?></p>
    <form action="" method="post">
        <textarea name="txt" id="txt" cols="80" rows="10"><?=htmlspecialchars($txt)?></textarea><br />
        <input type="submit" name="ok" value=" Apaidāpā! " />
    </form>
</body>
</html>
