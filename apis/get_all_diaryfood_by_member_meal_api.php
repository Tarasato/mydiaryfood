<?php //get_all_diaryfood_by_member_api.php ดึงข้อมูลเฉพาะข้อมูลการกินของสมาชิกคนนั้นๆตามมื้ออาหารที่ระบุ
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET"); //POST, PUT, DELETE
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once "./../connectdb.php";
require_once "./../models/diaryfood.php";

//สร้าง Instance (Object/ตัวแทน)
$connDB = new ConnectDB();
$diaryfood = new Diaryfood($connDB->getConnectionDB());

$data = json_decode(file_get_contents("php://input"));

$diaryfood->memId = $data->memId;
$diaryfood->foodMeal = $data->foodMeal;
//เรียกใช้ฟังก์ชันดึงข้อมูลทั้งหมดจากตาราง diaryfood_tb
$result = $diaryfood->getAllDiaryfoodByMemMeal();

//ตรวจสอบข้อมูลจากการเรัยกใช้ฟังก์ชันตรวจสอบชื่อผู้ใช้ รหัสผ่าน
if ($result->rowCount() > 0) {
    //มี
    $resultInfo = array();

    //Extract ข้อมูลที่ได้มาจากคำสั่ง SQL เก็บในตัวแปร
    while ($resultData = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($resultData);
        //สร้างตัวแปรอาร์เรย์เก็บข้อมูล
        $resultArray = array(
            "message" => "1",
            "foodId" => strval($foodId),
            "foodShopname" => $foodShopname,
            "foodMeal" => strval($foodMeal),
            "foodImage" => $foodImage,
            "foodPay" => strval($foodPay),
            "foodDate" => $foodDate,
            "foodProvince" => $foodProvince,
            "foodLat" => strval($foodLat),
            "foodLng" => strval($foodLng),
            "memId" => strval($memId)
        );
        array_push($resultInfo, $resultArray);
    }


    echo json_encode($resultInfo, JSON_UNESCAPED_UNICODE);
} else {
    $resultInfo = array();
    $resultArray = array(
        "message" => "0"
    );
    array_push($resultInfo, $resultArray);
    echo json_encode(array("message" => "0"));
}
