<?php
session_start();
//połączenie
include_once("laczenieZbaza.php");

//identyfikacja
$mail = $_SESSION['email'];
$pyt_o_id = "SELECT id FROM loginy WHERE login like '$mail'";
$wykonanie = $conn->query($pyt_o_id);
if($wykonanie->num_rows > 0){
    while($linia = $wykonanie->fetch_assoc()) {
        $id = $linia['id'];
    }
}

//Zapis danych
if(!empty($_POST["nazwa"])){
    $nazwa = $_POST["nazwa"];
    $pyt_o_nazwe = "UPDATE dane_konta SET nazwa = '$nazwa' WHERE id_loginu = '$id'";
    $wyslij1 = $conn->query($pyt_o_nazwe);
    $_SESSION['nickname'] = $nazwa;
    
}

if(!empty($_POST["data_uro"])){
    $data_uro = $_POST["data_uro"];
    $pyt_o_uro = "UPDATE dane_konta SET data_urodzenia = '$data_uro' WHERE id_loginu = '$id'";
    $wyslij2 = $conn->query($pyt_o_uro);
    
}

if(!empty($_POST["tel"])){
    $tel = $_POST["tel"];
    $pyt_o_tel = "UPDATE dane_konta SET nr_tel = '$tel' WHERE id_loginu = '$id'";
    $wyslij3 = $conn->query($pyt_o_tel);
    
}

if(!empty($_POST["miasto"])){
    $miasto = $_POST["miasto"];
    $pyt_o_miasto = "UPDATE dane_konta SET miasto = '$miasto' WHERE id_loginu = '$id'";
    $wyslij4 = $conn->query($pyt_o_miasto);
    
}

if(!empty($_POST["kraj"])){
    $kraj = $_POST["kraj"];
    $pyt_o_kraj = "UPDATE dane_konta SET kraj = '$kraj' WHERE id_loginu = '$id'";
    $wyslij5 = $conn->query($pyt_o_kraj);
    
}

if(!empty($_POST["opis"])){
    $opis = $_POST["opis"];    
    $pyt_o_opis = "UPDATE dane_konta SET opis_konta = '$opis' WHERE id_loginu = '$id'";
    $wyslij6 = $conn->query($pyt_o_opis);
    
}

if(!empty($_FILES['photo']['name']) && isset($_FILES['photo']['name'])){
//nazwa zdjecia i rozszerzenie
$nazwa_zdjecia = $_FILES["photo"]["name"];
$zdjecietemp = $_FILES["photo"]["tmp_name"];
$rozszerzenie_zdjecia = mime_content_type($zdjecietemp);

//sprawdzanie formatu
if($rozszerzenie_zdjecia == "image/png" || $rozszerzenie_zdjecia == "image/jpg" || $rozszerzenie_zdjecia == "image/jpeg"){

    if(is_uploaded_file($zdjecietemp)) {

        //nowa nazwa z datą
        $zdjecie_bez_roz = explode(".",$nazwa_zdjecia);
        $nowa_nazwa_zdjecia = date("Y-m-d-H-i-s") . '.' . $zdjecie_bez_roz[1];
        $sciezka = "avatary/";
        $sciezka_do_bazy = $sciezka . $nowa_nazwa_zdjecia;

        //stare zdjecie
        $pyt_zdjecie_z_bazy = "SELECT avatar FROM dane_konta WHERE id_loginu = '$id'";
        $pytanie = $conn->query($pyt_zdjecie_z_bazy);

        if($pytanie->num_rows > 0){
            while($linia = $pytanie->fetch_assoc()) {
            $stare_zdjecie = $linia['avatar'];
            }
        }

        $zdjecie_do_usuniecia = $stare_zdjecie;

        //usuniecie starego zdjecia
        if(file_exists($zdjecie_do_usuniecia)) {
            unlink($zdjecie_do_usuniecia);
        } 
        else{
            $_SESSION['wiadomosc_o_zdjeciu'] = 'Błąd zdjecie nie nadpisuje się! ';
        }

        //nowe zdjecie
        $pyt_o_zdjecie = "UPDATE dane_konta SET avatar = '$sciezka_do_bazy' WHERE id_loginu = '$id'";
            
            if(move_uploaded_file($zdjecietemp, $sciezka . $nowa_nazwa_zdjecia)) {
                $wyslij7 = $conn->query($pyt_o_zdjecie);
            }
            else {
                $_SESSION['wiadomosc_o_zdjeciu'] = "Nie udało sie umieścić zdjecia!";
        }
    }
    else {
        $_SESSION['wiadomosc_o_zdjeciu']= "Nie udało sie zapisać zdjecia!";
    }
}
else{
    $_SESSION['wiadomosc_o_zdjeciu']= "Zdjęcie może być tylko w formacie jpg, jpeg, png !";
}

}
$conn->close();
header('location:konto.php');
?>