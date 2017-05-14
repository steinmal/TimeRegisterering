<?php

function isProsjektOwner(ProsjektRegister $ProsjektReg, $prosjektId){
    $prosjekt = $ProsjektReg->hentProsjekt($prosjektId);
    return $_SESSION['brukerTilgang']->isProductOwner() && $prosjekt->getOwner() == $_SESSION['bruker']->getId();
}

function isProsjektLeder(ProsjektRegister $ProsjektReg, $prosjektId){
    $prosjekt = $ProsjektReg->hentProsjekt($prosjektId);
    return $_SESSION['brukerTilgang']->isProsjektAdmin() && $prosjekt->getLeder() == $_SESSION['bruker']->getId();
}

function isProsjektTeamLeder(BrukerRegister $BrukerReg, $prosjektId){
    $teamLeder = $BrukerReg->getTeamLederForProsjekt($prosjektId);
    return $_SESSION['brukerTilgang']->isTeamLeder() && $teamLeder == $_SESSION['bruker']->getId();
}

function isProductOwner(){
    return $_SESSION['brukerTilgang']->isProductOwner();
}

function isProsjektAdmin(){
    return $_SESSION['brukerTilgang']->isProsjektAdmin();
}

function isBrukerAdmin(){
    return $_SESSION['brukerTilgang']->isBrukerAdmin();
}

function isSystemAdmin(){
    return $_SESSION['brukerTilgang']->isSystemAdmin();
}

function isTeamLeder($TeamReg = null, $teamId = null){
    if($teamReg != null && $teawmId != null){
        $team = $TeamReg->hentTeam($teamId);
        return $_SESSION['brukerTilgang']->isTeamLeder() && $team->getLeder() == $_SESSION['bruker']->getId(); 
    }
    return $_SESSION['brukerTilgang']->isTeamLeder();
}

function isInnlogget(){
    return isset($_SESSION['innlogget']) && $_SESSION['innlogget'] == true && isset($_SESSION['brukerTilgang']);
}

function isAktiv(){
    return $_SESSION['bruker']->isAktivert();
}

?>