<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST"); //POST, PUT, DELETE
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once "./../connectdb.php";
require_once "./../models/diaryfood.php";

//สร้าง Instance (Object/ตัวแทน)
$connDB = new ConnectDB();
$diaryfood = new diaryfood($connDB->getConnectionDB());

//รับค่าจาก Client/User ซึ่งเป็น JSON มา Decode เก็บในตัวแปร
$data = json_decode(file_get_contents("php://input"));

//เอาค่าในตัวแปรกำหนดให้กับ ตัวแปรของ Model ที่สร้างไว้
$diaryfood->foodShopname = $data->foodShopname;
$diaryfood->foodMeal = $data->foodMeal;
$diaryfood->foodPay = $data->foodPay;
$diaryfood->foodDate = $data->foodDate;
$diaryfood->foodProvince = $data->foodProvince;
$diaryfood->foodLat = $data->foodLat;
$diaryfood->foodLng = $data->foodLng;
$diaryfood->memId = $data->memId;

//------อัพรูปแบบ Base 64-------
//เก็บรูป Base64 ไว้ในตัวแปร
$picture_temp = $data->foodImage;
//ตั้งชื่อรูปใหม่เพื่อใช้กับรูปที่เป็น Base 64 ที่ส่งมา
$picture_filename = "pic_" . uniqid() . "_"  . round(microtime(true)*1000) . ".jpg";
//เอารูปที่เป็น Base64 แปลงเป็นรูปแล้วเก็บไว้ใน picupload/food/
//file_put_contents(ที่อยู่ของไฟล์+ชื่อไฟล์, ตัวไฟล์ที่จะอัปโหลดไว้)
file_put_contents("./../picupload/food/" . $picture_filename, base64_decode($picture_temp));
//เอาชื่อไฟล์ไปกำหนดให้กับตัวแปรที่จะเก็บลงในฐานข้อมูล
$diaryfood->foodImage = $picture_filename;
//---------------------------------

$result = $diaryfood->insertDiaryFood();

//ตรวจสอบข้อมูลจากการเรัยกใช้ฟังก์ชันตรวจสอบชื่อผู้ใช้ รหัสผ่าน
if ($result == true) {
    //insert-update-delete สำเร็จ
    $resultArray = array(
        "message" => "1"
    );
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
} else {
    //insert-update-delete ไม่สำเร็จ
    $resultArray = array(
        "message" => "0"
    );
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}







?>