<?php
// Deklarerar funktionen
function create_csv_from_country_code($user_input){

    // Deklarerar variablar för både error och wildcard. Jag sätter dessa till 'false' från början när skriptet körs.
    $error      = false;
    $wildcard   = false;

    // Om landskoden är två bokstäver kollar en if-sats om första bokstaven är ett alfabetiskt tecken och om andra är en stjärna. Utvärderas detta till 'true' blir $wildcard = true och de två nedstående if-satser ignoreras.
    if(strlen($user_input) == 2)
        if(ctype_alpha($user_input[0]) == true && $user_input[1] == '*'){
            $wildcard   = true;
            $user_input = $user_input[0];
        }

    // Om det inte är ett wildcard körs en if-sats för att validera landskoden. Den får endast vara två tecken lång och teckena måste finnas i alfabetet. Annars blir $error = true.
    if($wildcard !== true)
        if(ctype_alpha($user_input) == false || strlen($user_input) !== 2)
            $error = true;
    
    // Utvärderas $error till 'true' inkluderas en PHP-fil som meddelar användaren på skärmen.
    if($error == true)
      include 'views/err.php';
      
    // Deklarerar variablar. För att det inte ska spela någon roll om användaren skriver med små eller stora bokstäver sätter jag en strtoupper runt den inskrivna landskoden. 
    $price                  = [];
    $code                   = strtoupper($user_input);
    $status                 = 'Failure';

    // En funktion som hämtar datan från CSV-filen och gör om denna till en associative array.
    function csv_file_to_array($csv_filename='data/international-orders.csv', $delimiter=','){

        if(!file_exists($csv_filename))

            return false;
            $csv_header = null; 
            $csv_data = [];

        if (($handle = fopen($csv_filename, 'r')) == true)
        
            while (($csv_row = fgetcsv($handle, 1000, $delimiter)) == true){
                if(!$csv_header)
                    $csv_header = $csv_row;
                else
                    $csv_data[] = array_combine($csv_header, $csv_row);
            }
            fclose($handle);

        return $csv_data;

    }

    // Om $wildcard är 'true' körs en foreach som letar igenom all data och försöker hitta en match.
    if($wildcard == true){
        foreach(csv_file_to_array() as $order){
            if ($order['ID'][1] == $code)
                $code = substr($order['ID'], 1, 2);
        }
    }

    // Lägger in priserna om en landskod hittas. $status blir då 'Success' istället för 'Failure'.
    foreach(csv_file_to_array() as $order){
        if (substr($order['ID'], 1, 2) == $code){
            $status     = 'Success';
            $price[]    = $order['Pris'];
        }
    }

    // Räknar ut summan av $price med hjälp av array_sum() och lägger detta i en ny variabel.
    $sum_of_price = round(array_sum($price));

    // Om $status inte är 'Failure' och $error inte är 'true' körs funktionen som genererar en ny CSV-fil.
    if($status !== 'Failure' && $error !== true){

        // En array som senare kommer genereras till CSV-data. Arrayen överst i $csv_array är 'rubrikerna', och arrayer som ligger under blir värden kopplade till titlarna.
        $csv_array = array(
            ['Status', 'Landskod', 'Totalsumma'], 
            [$status, $code, $sum_of_price]
        );
        
        // Hämtar lokal svensk tid och lägger i en variabel
        $local_hour = gmdate('h', time()) + 2; if(strlen($local_hour) == 1) {$local_hour = 0 . $local_hour;}
        $local_date = gmdate('Ymd-' . $local_hour . 'is', time());
        
        // Gör en variabel och bestämmer hur filnamnet ska formateras. Även i vilken mapp de skapade filerna ska hamna.
        $csv_file_name = "$code $local_date .csv";
        $csv_file_dest = "created_csv_files/$code-$local_date .csv";

        // Skapar den nya CSV-filen och lägger in datan.
        $csv_file = fopen($csv_file_dest, 'w');
        foreach ($csv_array as $csv_fields)
            fputcsv($csv_file, $csv_fields);
        fclose($csv_file);
        
        $view_code          = $code;
        $view_image         = 'https://www.countryflags.io/' . $code . '/flat/64.png';
        if($code == 'EN'){
            $view_image = 'https://www.countryflags.io/gb/flat/64.png';
        }
        $view_code_found    = '<span class="code-result"><span id="found">' . $code . '</span>' . '<span>kunde hittas</span></span>';
        $view_file_name     = 'Fil skapad: ' . $csv_file_name;
        $view_price         = $sum_of_price;
        $view_icon          = '<i style="color: #black;" class="fas fa-check-circle"></i>';

    } else {
        $view_code          = $status;
        $view_image         = 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/High-contrast-face-sad.svg/1024px-High-contrast-face-sad.svg.png';
        $view_code_found    = '<span class="code-result"><span id="not-found">' . $code . '</span>' . '<span>kunde ej hittas</span></span>';
        if(strlen($code) == 1){ $view_code_found = '<span class="code-result"><span id="not-found">' . $code . '</span>' . '<span>' . $code . ': Bokstaven finns inte i listan</span></span>';}
        $view_file_name     = 'Försök igen';
        $view_price         = $status;
        $view_icon          = '<i style="color: black" class="fas fa-times-circle"></i>';
    }

    // Om $error inte är 'false' inkluderas en PHP-fil som visualiserar resultatet av funktionen i webbläsaren.
    if($error == false)
        include 'views/result.php';
}

// Gör det möjligt att skapa nya CSV-filer via inputfältet i webbläsaren
if(isset($_POST['ok']))
    create_csv_from_country_code($_POST['country']);

// Funktionen som tömmer mappen med de skapade filerna
if(isset($_POST['del']))
    array_map('unlink', glob("created_csv_files/*"));

?>