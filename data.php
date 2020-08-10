<?php
$db = new PDO (
    "mysql:host=localhost;dbname=penzion;charset=utf8", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

//trida Stranka
class Stranka {
      //vlastnosti tridy
    protected $id;
    protected $titulek;
    protected $navigace;
    protected $obsah;
    protected $oldID;

    //tato funkce se spusti pri vytvoreni instance
    function __construct($id, $titulek, $navigace, $obsah)
    {
        $this->id=$id;
        $this->titulek=$titulek;
        $this->navigace=$navigace;
        $this->obsah = $obsah;
    }

    //staticke metody
    static function aktualizujPoradiVsechStranek ($poleId) {
      
        foreach ($poleId as $index => $id) {
            $query = $GLOBALS["db"]->prepare("UPDATE stranka set poradi=? Where id=?");        
            $query->execute([$index, $id]);
        }
    }



    //gettery
    function getTitulek() {
        return $this->titulek;
    }

    function setTitulek ($novyTitulek) {
        $this->titulek = $novyTitulek;
    }

    function getNavigace() {
        return $this->navigace;
    }

    function setNavigace ($novaNavigace) {
        $this->navigace = $novaNavigace;
    }

    function getId () {
        return $this->id;
    }

    function setId ($noveId) {
        $this->oldId = $this->id;
        $this->id = $noveId;
    }

    function getObsah () {
        return $this->obsah;
    }

    function setObsah ($novyObsahStranky) {
        $this->obsah=$novyObsahStranky;
    }

    function ulozitInstanciDoDatabaze () {
        if ($this->oldId != "") {

        $query = $GLOBALS["db"]->prepare("UPDATE stranka SET id=?, titulek=?, navigace=?, obsah=? WHERE id=?"); //globals rekne instanci ze ma hledat promenou mimo svoji classu, jelikoz je $db definovana mimo klasu tak ji klasa automaticky nezahrnuje
        $query->execute([$this->id, $this->titulek, $this->navigace, $this->obsah, $this->oldId]); // execute(array($this->id, $this->titulek, $this->navigace, $this->obsah));
        }
        else {
            $query = $GLOBALS["db"]->prepare("SELECT MAX(poradi) AS poradi FROM stranka");
            $query->execute();
            $row = $query->fetch();
            $maximalniCislo = $row["poradi"];

            $query = $GLOBALS["db"]->prepare("INSERT INTO stranka SET id=?, titulek=?, navigace=?, obsah=?, poradi=?");
            $query->execute([$this->id, $this->titulek, $this->navigace, $this->obsah, ++$maximalniCislo]);
        }
    }

    function smazSe () {
        $query = $GLOBALS["db"]->prepare("DELETE FROM stranka where id=?");
        $query->execute([$this->id]);
    }

}

$poleStranek = array();

$query = $db -> prepare("SELECT * FROM stranka ORDER BY poradi");
$query->execute(); //kdybych mel where = .... napitu to sem

$rows = $query->fetchAll();

//die(var_dump($rows)); //konec stranky, reknu programu aby dal uz nic nezpracovával

foreach ($rows as $row) {
    $poleStranek[$row["id"]] = new Stranka($row["id"], $row["titulek"], $row["navigace"],$row["obsah"]);  
}


//die(var_dump($polestranek));


/*
//pole instanci
$poleStranek = array(
    "domu" => new Stranka("domu", "PrimaPenzion", "Domů"),
    "kontakt" => new Stranka("kontakt", "Jak nás kontaktujete", "Kontakt"),
    "galerie" => new Stranka("galerie", "Galerie", "Galerie"),
    "rezervace" => new Stranka("rezervace", "Rezervační formulář", "Rezervace"),
    "404" => new Stranka("404", "Stránka neexistuje", "")
);
*/


/*$poleStranek = array(
    "domu" => array(
        "titulek" => "PrimaPenzion",
        "navigace" => "Domů",
    ),
    "kontakt" => array(
        "titulek" => "Jak nás kontaktujete",
        "navigace" => "Kontakt",
    ),
    "galerie" => array(
        "titulek" => "Galerie",
        "navigace" => "Galerie",
    ),
    "rezervace" => array(
        "titulek" => "Rezervační formulář",
        "navigace" => "rezervace",
    ),
    "404" => array(
		"titulek" => "Stránka neexistuje",
		"navigace" => "",
	),

);*/


