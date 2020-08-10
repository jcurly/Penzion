<?php
session_start();

//pripojime soubor ktery v sobe drzi informaci o strankach
require "data.php";
$aktualniInstanceStranky = null;


//chce se prihlasit
if (array_key_exists("login", $_POST)) {
    if ($_POST["username"] == "admin" && $_POST["password"] == "kocka123") {
        $_SESSION["prihlaseny-uzivatel"] = $_POST["username"];
    }else{
        echo "Spatne prihlasovaci udaje!";
        echo "<br/>";
    }
}

if (array_key_exists("logout", $_GET)) {
    unset($_SESSION["prihlaseny-uzivatel"]);
    header("Location: ?");
    exit;
}

if (array_key_exists("edit", $_GET)) {
    //vytahnu si z URL id stranky
    $idStranky = $_GET["edit"];

    //instance jsou v promenne $poleStranek ulozene pod ruznymi klicy
    $aktualniInstanceStranky = $poleStranek[$idStranky];
}

//uzivatel chce pridat stranku
if (array_key_exists("pridat", $_GET)) {
    $aktualniInstanceStranky = new Stranka("", "", "", "");
}

//uzivatel aktualizuje stranku
if (array_key_exists("aktualizovat", $_POST)) {
    $novyObsah = $_POST["obsah-stranky"];
    $noveId = $_POST["id-stranky"];
    $novyTitulek = $_POST["titulek-stranky"];
    $novaNavigace= $_POST["navigace-stranky"];

    $aktualniInstanceStranky->setId($noveId);
    $aktualniInstanceStranky->setTitulek($novyTitulek);
    $aktualniInstanceStranky->setNavigace($novaNavigace);
    $aktualniInstanceStranky->setObsah($novyObsah);

    $aktualniInstanceStranky->ulozitInstanciDoDatabaze();

    header("Location: ?edit={$aktualniInstanceStranky->getId()}");
    exit();
}

//uzivatel chce smazat stranku
if (array_key_exists("smazat", $_GET)) {
    $idStrankyKeSmazani = $_GET["smazat"];

    $poleStranek[$idStrankyKeSmazani]->smazSe();

    header("Location: ?");
    exit;
}

//uzivatel chce aktualizovat poradi stranek
if (array_key_exists("novePoradi", $_POST)) {
    $poleSerazenychId = $_POST["novePoradi"];

    //zavolame metodu ktera to aktualizuje v DB
    Stranka::aktualizujPoradiVsechStranek($poleSerazenychId);

    //ukoncime
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
</head>
<body>

    <?php
    //uzivatel je prihlasen
        if (array_key_exists("prihlaseny-uzivatel", $_SESSION)) {
            //pokud je v href ? pak to co je za tim jsou paramatery URL
            echo "<a href='?logout'>Odhlasit se</a>";
            ?>
                <ul id="seznam">
                    <?php
                    foreach ($poleStranek as $instanceStranky) {
                        echo "<li id='{$instanceStranky->getId()}'>
                                <a href='?edit={$instanceStranky->getId()}'>{$instanceStranky->getId()}</a> 
                                <a class='delete-button' href='?smazat={$instanceStranky->getId()}'> [SMAZAT] </a>
                            </li>";
                    }
                    ?>
                </ul>

                <a href="?pridat">Nova stranka</a>
            <?php
            
            //pokud $aktualniInstanceStranky neni null pak vime, ze uzivatel kliknul na to ze chce editovat stranku
            if ($aktualniInstanceStranky != null) {
                ?>

                <form method="POST">
                    
                    <label for="">ID: </label>
                    <input type="text" name="id-stranky" value="<?php echo $aktualniInstanceStranky->getId() ?>">

                    <label for="">Titulek: </label>
                    <input type="text" name="titulek-stranky" value="<?php echo $aktualniInstanceStranky->getTitulek() ?>">

                    <label for="">Navigace: </label>
                    <input type="text" name="navigace-stranky" value="<?php echo $aktualniInstanceStranky->getNavigace() ?>">
                    
                    <textarea name="obsah-stranky" id="obsah-stranky-textarea" cols="30" rows="30">
                        <?php echo htmlspecialchars($aktualniInstanceStranky->getObsah()); ?>
                    </textarea>
                    <input type="submit" value="Aktualizovat stranku" name="aktualizovat">
                </form>

                <script src="./vendor/tinymce/js/tinymce/tinymce.min.js"></script>
                    <script>
                        //selector: #idtextareay
                        tinymce.init({
                            selector: "#obsah-stranky-textarea",
                            plugins: [
                                    "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                                    "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                                    "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
                            ],
                            toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                            toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                            image_advtab: true ,
                            external_filemanager_path:"vendor/filemanager/",
                            external_plugins: { "filemanager" : "plugins/responsivefilemanager/plugin.min.js"},
                            filemanager_title:"Responsive Filemanager",
                            entity_encoding:'raw',
                            verify_html: false,
                        });
                    </script>

                <?php
            }

        }else{ //uzivatel je neprihlasen
            ?>
                <form method="POST">
                    <input type="text" name="username">
                    <input type="password" name="password" >
                    <input type="submit" value="Prihlasit se" name="login">
                </form>
            <?php
        }
    ?>
   

    <script src="./vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="./vendor/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="./js/admin.js"></script>

</body>
</html>