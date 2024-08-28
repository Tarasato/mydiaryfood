<?php
class diaryfood
{
    //ตัวแปรที่ใช้เก็บการติดต่อฐานข้อมูล
    private $connDB;

    //ตัวแปรที่ทำงานคู่กับคอลัมน์(ฟิวล์)ในตาราง
    public $foodId;
    public $foodShopname;
    public $foodMeal;
    public $foodImage;
    public $foodPay;
    public $foodDate;
    public $foodProvince;
    public $foodLat;
    public $foodLng;
    public $memId;

    //ตัวแปรสารพัดประโยชน์
    public $message;

    //constructor
    public function __construct($connDB)
    {
        $this->connDB = $connDB;
    }
    //----------------------------------------------
    //ฟังก์ชันการทำงานที่ล้อกับส่วนของ APIs

    //ฟังก์ชันดึงข้อมูลทั้งหมดจากตาราง diaryfood
    public function getAllDiaryfood()
    {
        //ตัวแปรเก็บคำสั่ง SQL
        $strSQL = "SELECT * FROM diaryfood_tb";

        //สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);

        //สั่งให้ SQL ทำงาน
        $stmt->execute();

        //ส่งค่าผลการทำงานกลับไปยังจุดเรียกใช้ฟังก์ชันนี้
        return $stmt;
    }
}
