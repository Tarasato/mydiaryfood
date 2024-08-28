<?php

class Member{
    //ตัวแปรที่ใช้เก็บการติดต่อฐานข้อมูล
    private $connDB;

    //ตัวแปรที่ทำงานคู่กับคอลัมน์(ฟิวล์)ในตาราง
    public $memid;
    public $memFullname;
    public $memEmail;
    public $memUsername;
    public $memPassword;
    public $memAge;

    //ตัวแปรสารพัดประโยชน์
    public $message;

    //constructor
    public function __construct($connDB) {
        $this->connDB = $connDB;
    }
    //----------------------------------------------
    //ฟังก์ชันการทำงานที่ล้อกับส่วนของ APIs

    //ฟังชันก์ตรวจสอบชื่อผู้ใช้และรหัสผ่าน
    public function checkLogin(){
        //ตัวแปรเก็บคำสั่ง SQL
        $strSQL = "SELECT * FROM member_tb WHERE memUsername = :memUsername AND memPassword = :memPassword";

        //ตรวจสอบค่าที่ถูกส่งจาก Client/User ก่อนที่จะกำหนดให้กับ parameters (:????)
        $this->memUsername = htmlspecialchars(strip_tags($this->memUsername));
        $this->memPassword = htmlspecialchars(strip_tags($this->memPassword));

        //สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);

        //เอาที่ผ่านการตรวจแล้วไปกำหนดให้กับ parameters
        $stmt->bindParam(":memUsername", $this->memUsername);
        $stmt->bindParam(":memPassword", $this->memPassword);

        //สั่งให้ SQL ทำงาน
        $stmt->execute();

        //ส่งค่าผลการทำงานกลับไปยังจุดเรียกใช้ฟังก์ชันนี้
        return $stmt;
    }
}
