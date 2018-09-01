<div class="div">
    <h4 style="border-bottom:1px solid rgba(0, 0, 0, 0.2); padding-bottom:0.5rem;margin-bottom:0.5rem;">Skapad senast:</h4>
    <div style="display:flex;flex-direction:row;justify-content:space-between">
        <span>Antal: <?= count($generated_csvs); ?></span>
        <form id="form-del" method="post">
        <button id="btn-del" type="submit" name="del"><i class="fa fa-trash"></i></button>
        </form>
    </div>

    <ul id="created-countries-list">
        <?php 
            foreach(array_unique($generated_csvs) as $key => $csv_name) {
                $csv_file_country_code = $csv_name[0] . $csv_name[1];
        ?>
                <li>
                    <span class="created-countries"><?= $key + 1 . '. ' . $csv_name ?></span>
                    <span>
                        <?php
                            if($csv_file_country_code != 'EN'):
                        ?>
                            <img src="https://www.countryflags.io/<?= $csv_file_country_code ?>/shiny/24.png" alt="">
                        <?php 
                            else:
                        ?>
                            <img src="https://www.countryflags.io/gb/shiny/24.png" alt="">
                        <?php
                            endif;
                        ?>
                    </span>
                </li>
        <?php
            } 
        ?>
    </ul>
</div>