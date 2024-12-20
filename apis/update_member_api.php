<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT"); //POST, PUT, DELETE
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once "./../connectdb.php";
require_once "./../models/member.php";

//สร้าง Instance (Object/ตัวแทน)
$connDB = new ConnectDB();
$member = new Member($connDB->getConnectionDB());

//รับค่าจาก Client/User ซึ่งเป็น JSON มา Decode เก็บในตัวแปร
$data = json_decode(file_get_contents("php://input"));

//เอาค่าในตัวแปรกำหนดให้กับ ตัวแปรของ Model ที่สร้างไว้
$member->memId = $data->memId;
$member->memEmail = $data->memEmail;
$member->memUsername = $data->memUsername;
$member->memPassword = $data->memPassword;
$member->memAge = $data->memAge;

//กรณีแก้ไขต้องตรวจสอบก่อนว่ามีการอัพโหลดรูปไหมมาหรือไม่
if(isset($data->memImage)){
    //------อัพรูปแบบ Base 64-------
    //เก็บรูป Base64 ไว้ในตัวแปร
    $picture_temp = $data->memImage;
    //ตั้งชื่อรูปใหม่เพื่อใช้กับรูปที่เป็น Base 64 ที่ส่งมา
    $picture_filename = "mem_" . uniqid() . "_"  . round(microtime(true)*10) . ".png";
    //เอารูปที่เป็น Base64 แปลงเป็นรูปแล้วเก็บไว้ใน picupload/food/
    //file_put_contents(ที่อยู่ของไฟล์+ชื่อไฟล์, ตัวไฟล์ที่จะอัปโหลดไว้)
    file_put_contents("./../assets/images/picupload/memImages/" . $picture_filename, base64_decode($picture_temp));
    //เอาชื่อไฟล์ไปกำหนดให้กับตัวแปรที่จะเก็บลงในฐานข้อมูล
    $member->memImage = $picture_filename;
    //---------------------------------
}else{
    $member->memImage = "";
}

//เรียกใช้ฟังก์ชันตรวจสอบชื่อผู้ใช้ รหัสผ่าน
$result = $member->updateMember();

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