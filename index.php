<?php 
require "data.php";


// pokud uzivatel zvolil nejakou stranku tak mu ji zobrazime
// Pokud prisel aniz by neco zvolil tak mu zobrazime
// stranku "domu"
if (array_key_exists("stranka", $_GET))
{
    $stranka = $_GET["stranka"];

    // potrebujeme zkontrolovat zdali vybrana stranka
	// existuje. A pokud neexistuje tak misto toho
    // zobrazime nahradni stranku
    if(!array_key_exists($stranka, $poleStranek))
    {
        //stranka neexistuje
        $stranka = "404";
        http_response_code(404);
    }

}
else
{
    $stranka = array_keys($poleStranek)[0];
}




?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $poleStranek[$stranka]->getTitulek(); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/
    x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,100&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <div class="headerKon">
                <a href="tel:+420606123456">+420 606 123 456</a>
                <div class="headerIkony">
                    <a href="http://facebook.com" target=_blank><i class="fab fa-facebook"></i></a>
                    <a href="http://instagram.com" target=_blank><i class="fab fa-instagram"></i></a>
                    <a href="http://youtube.com" target=_blank><i class="fab fa-youtube"></i></a>
                    <a href="http://twitter.com" target=_blank><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <a href="./" class="logo">
                <p>Prima</p>
                <p>Penzion</p>
            </a>
            <div class="menu">
                <ul>
                    <?php 
                    foreach ($poleStranek as $kodStranky => $informaceOStrance)
                    {
                        if ($informaceOStrance->getNavigace() != "") {
                            echo "<li><a href='$kodStranky'>
                            {$informaceOStrance->getNavigace()}
                            </a></li>";
                        }
                        
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="headerObr"></div>
    </header>
    <section>
    <?php
    //require "$stranka.html"; // nacteme takovy soubor, ktery odpovida nasi
    // promenne $stranka s priponou .html

    //spustime knihovnu shortcode
    require "./vendor/shortcode-init.php";
     //prozeneme nas text shorcode procesorem
    $finalniText = ShortcodeProcessor::process($poleStranek[$stranka]->getObsah());
    
    //vypiseme vysledny text po zprocesovani
    echo $finalniText;
    ?>
    </section>
    <footer>
        <div class="pata">
            <div class="container">
                <div class="menu">
                    <ul>
                        <?php 
                      foreach ($poleStranek as $kodStranky => $informaceOStrance)
                      {
                          if ($informaceOStrance->getNavigace() != "") {
                              echo "<li><a href='$kodStranky'>
                              {$informaceOStrance->getNavigace()}
                              </a></li>";
                          }
                          
                      }
                        ?>
                    </ul>
                </div>
                <a href="index.html" class="logo">
                    <p>Prima</p>
                    <p>Penzion</p>
                </a>
                <p>
                    <i class="fas fa-map-pin"></i>
                    <a href="https://goo.gl/maps/MjmkwYR67bKzcAKD7" target="_blank">
                    PrimaPenzion, Jablonského 2, Praha 7
                    </a>
                </p>
                <p>
                    <i class="fas fa-phone-alt"></i>
                    <a href="tel:773348790"> 773 348 790</a>
                </p>
                <p>
                    <i class="far fa-envelope"></i>
                    <span>info@primapenzion.cz</span>
                </p>
                <div class="headerIkony">
                    <a href="http://facebook.com" target=_blank><i class="fab fa-facebook"></i></a>
                    <a href="http://instagram.com" target=_blank><i class="fab fa-instagram"></i></a>
                    <a href="http://youtube.com" target=_blank><i class="fab fa-youtube"></i></a>
                    <a href="http://twitter.com" target=_blank><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </footer> 

    <div id="top"></div>                   

    <script src="./vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="./js/main.js"></script>
    <?php
        require "./vendor/photoswipe-init.php";
    ?>


</body>
</html>
