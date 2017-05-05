<?php
// TODO: Refactor, dette kan være en del av en parent-klasse for Register-klassene.
// Da kan $type benyttes direkte

// TODO: Lag metode for pdo-prepare med try/catch

function execStmt(PDOStatement $stmt){
    try{
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        feil($e->getMessage());
    }
    return false;
}

function execStmtReturnId(PDOStatement $stmt, PDO $db){
    try{
        $stmt->execute();
        return $db->lastInsertId();
    } catch (Exception $e) {
        feil($e->getMessage());
    }
    return false;
}

function getEn(PDOStatement $stmt, $type){
    try{
        $stmt->execute();
        if($obj = $stmt->fetchObject($type)){
            return $obj;
        }
    } catch (Exception $e){
        feil($e->getMessage());
    }
}

function getAlle(PDOStatement $stmt, $type, $idAsArrayIndex = false){
    $ret = array();
    try {
        $stmt->execute();

        if($idAsArrayIndex){
            while ($obj = $stmt->fetchObject($type)) {
                $ret[$obj->getId()] = $obj;
            }
        } else {
            while ($obj = $stmt->fetchObject($type)) {
               $ret[] = $obj;
            }
        }
    } catch (Exception $e) {
        feil($e->getMessage());
    }
    return $ret;
}

function feil($feilmelding) {
    print "<h2>Oisann... Noe gikk galt</h2>";
    print "<h4>$feilmelding</h4>";
}
?>