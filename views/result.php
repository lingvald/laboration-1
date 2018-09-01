<div class="div" id="container">
    <img src="<?= $view_image ?>">
    <h1><?= $view_code_found ?></h1>
    <h2 class="success"><?= $view_file_name ?></h2>
    <ul>
        <li><span><strong>Status: </strong><?= $status ?></span><?= $view_icon ?></li>
        <li>
            <span><strong>Land: </strong>
            <?php 
                    if($status !== 'Failure'):
                        $view_country = json_decode(file_get_contents('data/countryNames.json'))->$view_code; 
                    else:
                       $view_country = $status;
                    endif;

                    if(strlen($view_code) == 0)
                        $view_country = 'Landet finns inte i listan';
             ?>
             <?= $view_country ?>
            </span><?= $view_icon ?>
        </li>
        <li><span><strong>Landskod: </strong><?= $view_code ?></span><?= $view_icon ?></li>
        <li><span><strong>Totalsumma: </strong><?= $view_price ?></span><?= $view_icon ?></li>
    </ul>
</div>