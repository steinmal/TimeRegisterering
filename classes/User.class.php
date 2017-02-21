<?php
    class User {
        private $brukernavn;
        private $passord;
        
        function __construct($brukernavn,$passord){
            $this->brukernavn = $brukernavn;
            $this->passord = $passord;
        }
        public static function login($db, $brukernavn, $passord) {
            $stmt = $db->prepare("SELECT Bruker_Epost, Bruker_Passord FROM Bruker WHERE Bruker_Epost=:email");
            $stmt->bindparam(':email', $brukernavn, PDO::PARAM_STR);
            $stmt->execute();
            
            if($rad = $stmt->fetch()) {
                $email = $rad['Bruker_Epost'];
                $hash = $rad['Bruker_Passord'];

                
            }
            if($passord == $hash) {
                    $_SESSION['innlogget'] = true;
                    $_SESSION['bruker'] = new User($email, $passord);
                    return true;
                }
            
            echo "Feil passord";
            return false;
        }
    }