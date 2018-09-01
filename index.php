<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style.css">
    <title>Laboration</title>
</head>
<body>  
    <h1 id="heading">International Orders CSV</h1>
    <div class="container-wrapper">
        <?php include 'generate_csv_from_country.php'; ?>
        <?php include 'prev_files.php'; ?>
        <?php if(!scan_dir('created_csv_files/')): ?>
            <div>
                <h2 class="div">Det finns inga CSV-filer</h2>            
            </div>
        <?php endif; ?>
    </div>
    <?php include 'views/form.php'; ?>
</body>
</html>

