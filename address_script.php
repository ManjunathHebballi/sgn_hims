<?php
include('model/dbconnect.php');

$places = [
    "Niddodi ","Moodabidri","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirtady","Nellikaru","Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Kallabettu","Moodabidri",
    "Marpady","Thodaru ","Moodabidri","Puthige","Niddodi","Bola","Kallamundkur","Mooduperar ","Moodabidri","Kinnigoli","Niddodi","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirthady","Nellikaru",
    "Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Badagumijaru","Daregudde","Hosabettu","Kallamundkooru","Karinje ","Moodabidri","Kellaputhige","Mantrady","Marpadi","Moodukonaje",
    "Padukonaje","Panapila","Prantya ","Moodabidri","Puchhamoguru","Puthige","Thodaru","Valpadi","Aikala","Moodabidri ","Moodabidri","Niddodi ","Moodabidri","Tenkamijar","Iruvail ","Moodabidri","Marooru",
    "Shirtady","Nellikaru","Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Kallabettu","Moodabidri","Marpady","Thodaru ","Moodabidri","Puthige","Niddodi","Bola","Kallamundkur",
    "Mooduperar ","Moodabidri","Kinnigoli","Niddodi","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirthady","Nellikaru","Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale",
    "Badagumijaru","Daregudde","Hosabettu","Kallamundkooru","Karinje ","Moodabidri","Kellaputhige","Mantrady","Marpadi","Moodukonaje","Padukonaje","Panapila","Prantya ","Moodabidri","Puchhamoguru","Puthige",
    "Thodaru","Valpadi","Aikala","Moodabidri ","Moodabidri","Niddodi ","Moodabidri","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirtady","Nellikaru","Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri"
    ,"Paladka","Kadandale","Kallabettu","Moodabidri","Marpady","Thodaru ","Moodabidri","Puthige","Niddodi","Bola","Kallamundkur","Mooduperar ","Moodabidri","Kinnigoli","Niddodi","Tenkamijar","Iruvail ",
    "Moodabidri","Marooru","Shirthady","Nellikaru","Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Badagumijaru","Daregudde","Hosabettu","Kallamundkooru","Karinje ","Moodabidri",
    "Kellaputhige","Mantrady","Marpadi","Moodukonaje","Padukonaje","Panapila","Prantya ","Moodabidri","Puchhamoguru","Puthige","Thodaru","Valpadi","Aikala","Moodabidri ","Moodabidri","Niddodi ","Moodabidri",
    "Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirtady","Nellikaru","Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Kallabettu","Moodabidri","Marpady","Thodaru ","Moodabidri",
    "Puthige","Niddodi","Bola","Kallamundkur","Mooduperar ","Moodabidri","Kinnigoli","Niddodi","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirthady","Nellikaru","Moodumarnadu","Padumarnadu","Beluvai",
    "Moodabidri","Paladka","Kadandale","Badagumijaru","Daregudde","Hosabettu","Kallamundkooru","Karinje ","Moodabidri","Kellaputhige","Mantrady","Marpadi","Moodukonaje","Padukonaje","Panapila","Prantya",
    "Moodabidri","Puchhamoguru","Puthige","Thodaru","Valpadi","Aikala","Moodabidri ","Moodabidri","Niddodi ","Moodabidri","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirtady","Nellikaru","Moodumarnadu",
    "Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Kallabettu","Moodabidri","Marpady","Thodaru ","Moodabidri","Puthige","Niddodi","Bola","Kallamundkur","Mooduperar ","Moodabidri","Kinnigoli",
    "Niddodi","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirthady","Nellikaru","Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Badagumijaru","Daregudde","Hosabettu",
    "Kallamundkooru","Karinje ","Moodabidri","Kellaputhige","Mantrady","Marpadi","Moodukonaje","Padukonaje","Panapila","Prantya ","Moodabidri","Puchhamoguru","Puthige","Thodaru","Valpadi","Aikala","Moodabidri",
    "Moodabidri","Niddodi ","Moodabidri","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirtady","Nellikaru","Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Kallabettu","Moodabidri",
    "Marpady","Thodaru ","Moodabidri","Puthige","Niddodi","Bola","Kallamundkur","Mooduperar ","Moodabidri","Kinnigoli","Niddodi","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirthady","Nellikaru","Moodumarnadu",
    "Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Badagumijaru","Daregudde","Hosabettu","Kallamundkooru","Karinje ","Moodabidri","Kellaputhige","Mantrady","Marpadi","Moodukonaje","Padukonaje",
    "Panapila","Prantya ","Moodabidri","Puchhamoguru","Puthige","Thodaru","Valpadi","Aikala","Moodabidri ","Moodabidri","Niddodi ","Moodabidri","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirtady","Nellikaru",
    "Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Kallabettu","Moodabidri","Marpady","Thodaru ","Moodabidri","Puthige","Niddodi","Bola","Kallamundkur","Mooduperar ","Moodabidri",
    "Kinnigoli","Niddodi","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirthady","Nellikaru","Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Badagumijaru","Daregudde","Hosabettu",
    "Kallamundkooru","Karinje ","Moodabidri","Kellaputhige","Mantrady","Marpadi","Moodukonaje","Padukonaje","Panapila","Prantya ","Moodabidri","Puchhamoguru","Puthige","Thodaru","Valpadi","Aikala","Moodabidri ",
    "Moodabidri","Niddodi ","Moodabidri","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirtady","Nellikaru","Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Kallabettu","Moodabidri",
    "Marpady","Thodaru ","Moodabidri","Puthige","Niddodi","Bola","Kallamundkur","Mooduperar ","Moodabidri","Kinnigoli","Niddodi","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirthady","Nellikaru","Moodumarnadu",
    "Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Badagumijaru","Daregudde","Hosabettu","Kallamundkooru","Karinje ","Moodabidri","Kellaputhige","Mantrady","Marpadi","Moodukonaje","Padukonaje",
    "Panapila","Prantya ","Moodabidri","Puchhamoguru","Puthige","Thodaru","Valpadi","Aikala","Moodabidri ","Moodabidri","Niddodi ","Moodabidri","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirtady","Nellikaru",
    "Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Kallabettu","Moodabidri","Marpady","Thodaru ","Moodabidri","Puthige","Niddodi","Bola","Kallamundkur","Mooduperar ","Moodabidri",
    "Kinnigoli","Niddodi","Tenkamijar","Iruvail ","Moodabidri","Marooru","Shirthady","Nellikaru","Moodumarnadu","Padumarnadu","Beluvai ","Moodabidri","Paladka","Kadandale","Badagumijaru","Daregudde","Hosabettu",
    "Kallamundkooru","Karinje ","Moodabidri","Kellaputhige","Mantrady","Marpadi","Moodukonaje","Padukonaje","Panapila","Prantya ","Moodabidri","Puchhamoguru","Puthige","Thodaru","Valpadi","Aikala","Moodabidri",
    "Moodabidri","MYSORE","KALLAMUNKUR","BELGAUM","BALLADI","VIJAYPUR","KARKALA","HOSMAR","CHAMARAJ NAGAR","BANGALORE","MANDYA","Thodar","KINNIGOLI","Mangalore","MASTIKATTE","Dharmasthala","KOPPALA","ASHWATHAPURA",
    "Gurupura","MOODBIDRI","Kallamundkur","BANTWALA","RENJALA","MANGALORE","BIDHAR","SURATHKAL","BALKUNJE","KUNDAPURA","VIDYAGIRI","HANDEL","YEDAPADAV","MAVINAKATTE","BAGAKOTE","KOPPA","DHARWAD","KALLABETTU",
    "BHADKALA","Kannur","UTTARA KANNADA","SIRSI","HAVER","BIJAI","Panemangalore","ALIYOOR","RAMDURGA","Uppinangady","KALBURGI","HOSPETE","KOLARA","GURUPURA","ALANGAR","Thokur","KERALA","Kuppepadavu","GANJIMATA",
    "KARAVARA","KODYADKA","MANGALAPADAV","Ullal","Kinnigoli","DHARMASTALA","BALLARI","Panambur","PUTTUR","THODANKILA","SURALPADY","Katipalla","Bajpe","HAVERI","Mulki","RAMNAGAR","TAMILNADU","Bantwal","Perinje",
    "PALADKA","BELTHANGADY","Bajala","Baikampady","Belthangady","GADAG","HUBLI","ANDRAPRADESH","B.C ROAD","SHIRTHADY","KATEEL","Thumbe","KOLAMBE","BELVAI","Kadandale","KAIKAMBA","HASAN","CHIKKAMANGALOR",
    "VIDYAGIRI","Kadaba","BIJAPURA","Kudripadavu","Pudu","Moodabidri","Vamanjoor","Niddodi","UDUPI","MIJAR","Kallabettu","Hosabettu","Puttur","DAVANAGERE","SHRINGERI","MUMBAI","Mudushedde","CHIKKABALLAPUR",
    "ONTIKATTE","Kateel","SULYA","KASARGOD","TUMKUR","Sullia","Kotekara","PADUBIDRI","BIHAR","HOSA MARNAD","KODAGU","CHITRADURGA","VIJAYANAGAR","YADGIRI","SAMPIGE","Maroor","SIDDAKATTE","BAGALKOTE","BELGALURU",
    "SHIVMOGA","BELAGAVI","Sampige","HEBRI","Madanthyar","Alangar","MAHARASTRA","JARIGEKATTE","VENOOR","Munnur","RAICHUR","CHIKAMAGALORE","GULBARGA","GOKAK","HYDRABAD","Ujire","MANIPAL","MADIKERI","GOA",
    "Nandavara","Padukonaje","VAMANJOOR","BANAGUDDE","UDUGANI","PUNE","PUTTIGE","THODAR","MANIPUR","MULKI","BAJPE"
];

 $newlogin = new userlogin;                  
    $conn=$newlogin->dbconnect();


// Get all rows from diagnosis_data
$result = $conn->query("SELECT id FROM temp");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $randomPlace = $places[array_rand($places)];
        $id = $row['id'];

        $stmt = $conn->prepare("UPDATE temp SET address = ? WHERE id = ?");
        $stmt->bind_param("si", $randomPlace, $id);
        $stmt->execute();
        $stmt->close();
    }
    echo "Addresses updated successfully.";
} else {
    echo "No records found in daignosis_data.";
}

$conn->close();
?>
