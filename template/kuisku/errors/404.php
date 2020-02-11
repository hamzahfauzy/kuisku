<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error 404</title>
    <link href="<?= asset('css/app.css') ?>" type="text/css" rel="stylesheet"/>
    <link href="<?= asset('css/style.css') ?>" type="text/css" rel="stylesheet"/>
</head>
<body>
    <center>
        <h1>Error 404</h1>
        <?= $message; ?>
        <br><br>
        <a href="<?= history()->back() ?>"><< Go Back</a>
    </center>
</body>
</html>