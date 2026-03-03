<?php
$screen = "0";

if (isset($_POST['btn'])) {

    $screen = $_POST['screen'];
    $btn = $_POST['btn'];

    if ($btn == "C") {
        $screen = "0";
    }

    else if ($btn == "=") {

        if (strpos($screen, "/0") !== false) {
            $screen = "Error";
        } 
        else {
            $result = eval("return $screen;");
            $screen = $result;
        }
    }

    else {

        if ($screen == "0") {
            $screen = $btn;
        } else {
            $screen = $screen . $btn;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>May tinh</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="display">
<form method="post">

    <input type="text" name="screen" value="<?php echo $screen; ?>" readonly>

    <div class="btn">
        <button type="submit" name="btn" value="7">7</button>
        <button type="submit" name="btn" value="8">8</button>
        <button type="submit" name="btn" value="9">9</button>
        <button type="submit" name="btn" value="/">/</button>

        <button type="submit" name="btn" value="4">4</button>
        <button type="submit" name="btn" value="5">5</button>
        <button type="submit" name="btn" value="6">6</button>
        <button type="submit" name="btn" value="*">*</button>

        <button type="submit" name="btn" value="1">1</button>
        <button type="submit" name="btn" value="2">2</button>
        <button type="submit" name="btn" value="3">3</button>
        <button type="submit" name="btn" value="-">-</button>

        <button type="submit" name="btn" value="0">0</button>
        <button type="submit" name="btn" value=".">.</button>
        <button type="submit" name="btn" value="=">=</button>
        <button type="submit" name="btn" value="+">+</button>

        <button type="submit" name="btn" value="C">C</button>
    </div>

</form>
</div>

</body>
</html>