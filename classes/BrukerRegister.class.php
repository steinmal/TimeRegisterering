<?php
require_once("classhelper.php");

class BrukerRegister {
    private $db;
    private $brukertyper;
    private $typeName = "Bruker";

    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    public function login($login, $passord) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM bruker WHERE (bruker_epost=:login OR bruker_navn=:login)");
            $stmt->bindparam(':login', $login, PDO::PARAM_STR);
            $stmt->execute();
            
            $bruker = $stmt->fetchObject('Bruker');
            
            if($bruker != null && password_verify($passord, $bruker->getPassord())) {
                $_SESSION['innlogget'] = true;
                $_SESSION['bruker'] = $bruker;
                $_SESSION['brukerTilgang'] = $this->getBrukertype($bruker->getBrukertype());
                return true;
            }
            $_POST['fail'] = true;
            return false;
        } catch (Exception $e) {
            feil($e->getMessage());
        }
    }

    public function opprettBruker($bruker) {
        try {
            $stmt = $this->db->prepare("INSERT INTO `bruker` (bruker_navn, bruker_epost, bruker_telefon, bruker_passord, bruker_registreringsdato, brukertype_id, bruker_aktivert, bruker_aktiveringskode)
            VALUES (:navn, :epost, :telefonnummer, :passord, now(), 4, 0, :aktiveringskode)");
            
            $aktiveringskode = sha1(uniqid(rand(), 1));
  
            $stmt->bindParam(':navn', $bruker->getNavn(), PDO::PARAM_STR);
            $stmt->bindParam(':epost', $bruker->getEpost(), PDO::PARAM_STR);
            $stmt->bindParam(':telefonnummer', $bruker->getTelefon(), PDO::PARAM_INT);
            $stmt->bindParam(':passord', $bruker->getPassord(), PDO::PARAM_STR);
            $stmt->bindParam(':aktiveringskode', $aktiveringskode, PDO::PARAM_STR);
            
            $stmt->execute();
            
            $to = $bruker->getEpost();
            $subject = 'Aktiveringslink for din bruker på timeregistrering';
            $message = 'Bruk denne linken: http://' . $_SERVER['HTTP_HOST'] . "/brukeraktivering.php?token=" . $aktiveringskode;
            EmailHelper::sendEmail($to, $subject, $message);
            
        } catch (Exception $e) {
            feil($e->getMessage());
        }
    }
    
    public function redigerBruker($bruker){
        $stmt = $this->db->prepare("UPDATE bruker SET brukertype_id=:type, bruker_navn=:navn, bruker_epost=:epost, bruker_telefon=:telefon, bruker_passord=:passord WHERE bruker_id=:id");
        
        $id = $bruker->getId();
        $brukertype = $bruker->getBrukertype();
        $brukernavn = $bruker->getNavn();
        $epost = $bruker->getEpost();
        $telefon = $bruker->getTelefon();
        $passord = $bruker->getPassord();
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $brukertype, PDO::PARAM_INT);
        $stmt->bindParam(':navn', $brukernavn, PDO::PARAM_STR);
        $stmt->bindParam(':epost', $epost, PDO::PARAM_STR);
        $stmt->bindParam(':telefon', $telefon, PDO::PARAM_INT);
        $stmt->bindParam(':passord', $passord, PDO::PARAM_STR);
        return execStmt($stmt);
    }

    
    public function hentAlleBrukere() {
        $stmt = $this->db->prepare("SELECT * FROM bruker");
        return getAlle($stmt, $this->typeName);
    }
    
    public function hentBruker($id) {
        $stmt = $this->db->prepare("SELECT * FROM bruker WHERE bruker_id=:id");
        $stmt->bindparam(':id', $id, PDO::PARAM_STR);
        return getEn($stmt, $this->typeName);
    }
    
    public function getAlleBrukertyper() {
        $stmt = $this->db->prepare("SELECT * FROM brukertype");
        return getAlle($stmt, "BrukerType", true);
    }
    
    public function getBrukertype($brukertype_id) {
        if ($this->brukertyper == null)
            $this->brukertyper = $this->getAlleBrukertyper();

        if (!isset($this->brukertyper[$brukertype_id]))
            throw new InvalidArgumentException('Usertype not defined: ' . $brukertype_id);

        return $this->brukertyper[$brukertype_id];
    }
    
    public function aktiverBruker($id){
        try {
            $stmt = $this->db->prepare("UPDATE `bruker` SET bruker_aktivert=1, bruker_aktiveringskode=:aktiveringskode WHERE bruker_id=:id");
            $aktiveringskode = "";
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':aktiveringskode', $aktiveringskode);
            $stmt->execute();
            
            $bruker = $this->hentBruker($id);
            $to = $bruker->getEpost();
            $subject = 'Din bruker på timeregistrering har blitt aktivert';
            $message = 'Din bruker på timeregistrering har blitt aktivert';
            EmailHelper::sendEmail($to, $subject, $message);
            
        } catch (Exception $e) {
            feil($e->getMessage());
        }
    }
    
    public function aktiverBrukerMedAktiveringskode($aktiveringskode){
        try {
            $stmt = $this->db->prepare("SELECT * FROM bruker WHERE bruker_aktiveringskode = :aktiveringskode");
            $stmt->bindParam(':aktiveringskode', $aktiveringskode);
            $stmt->execute();
            
            if($bruker = $stmt->fetchObject('Bruker')){
                $id = $bruker->getId();
                $this->aktiverBruker($id);
                return true;
            }
            return false;
        } catch (Exception $e) {
            feil($e->getMessage());
            return false;
        }

    }
    
    public function deaktiverBruker($id){
        $stmt = $this->db->prepare("UPDATE `bruker` SET bruker_aktivert=0 WHERE bruker_id=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return execStmt($stmt);
    }


    public function brukernavnEksisterer($brukernavn) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM bruker WHERE bruker_navn = :brukernavn");
            $stmt->bindParam(':brukernavn', $brukernavn);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                return true;
            }
            else
                return false;
        } catch (Exception $e) {
            feil($e->getMessage());
        }
    }
    
    public function emailEksisterer($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM bruker WHERE bruker_epost = :epost");
            $stmt->bindParam(':epost', $email);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                return true;
            }
            else
                return false;
        } catch (Exception $e) {
            feil($e->getMessage());
        }
    }
}