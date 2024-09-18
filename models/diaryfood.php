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

    //ฟังก์ชันดึงข้อมูลเฉพาะข้อมูลการกินของสมาชิกคนนั้นๆเท่านั้นจากตาราง diaryfood
    public function getAllDiaryfoodByMemId()
    {
        //ตัวแปรเก็บคำสั่ง SQL
        $strSQL = "SELECT * FROM diaryfood_tb" . " WHERE memId = :memId";

        $this->memId = intval(htmlspecialchars(strip_tags($this->memId)));

        //สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);
        $stmt->bindParam(":memId", $this->memId);
        //สั่งให้ SQL ทำงาน
        $stmt->execute();

        //ส่งค่าผลการทำงานกลับไปยังจุดเรียกใช้ฟังก์ชันนี้
        return $stmt;
    }

    //ฟังก์ชันดึงข้อมูลเฉพาะข้อมูลการกินของสมาชิกคนนั้นๆตามมื้ออาหารที่ระบุจากตาราง diaryfood
    public function getAllDiaryfoodByMemMeal()
    {
        //ตัวแปรเก็บคำสั่ง SQL
        $strSQL = "SELECT * FROM diaryfood_tb" . " WHERE memId = :memId AND foodMeal = :foodMeal";

        $this->memId = intval(htmlspecialchars(strip_tags($this->memId)));
        $this->foodMeal = intval(htmlspecialchars(strip_tags($this->foodMeal)));

        //สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);
        $stmt->bindParam(":memId", $this->memId);
        $stmt->bindParam(":foodMeal", $this->foodMeal);
        //สั่งให้ SQL ทำงาน
        $stmt->execute();

        //ส่งค่าผลการทำงานกลับไปยังจุดเรียกใช้ฟังก์ชันนี้
        return $stmt;
    }

    //ฟังก์ชันเพิ่มข้อมูลมื้ออาหาร
    public function insertDiaryFood()
    {
        //ตัวแปรเก็บคำสั่ง SQL
        $strSQL = "  INSERT INTO diaryfood_tb (`foodShopname`, `foodMeal`, `foodImage`, `foodPay`, `foodDate`, `foodProvince`, `foodLat`, `foodLng`, `memId`) VALUES (:foodShopname, :foodMeal, :foodImage, :foodPay, :foodDate, :foodProvince, :foodLat, :foodLng, :memId);";

        //ตรวจสอบค่าที่ถูกส่งจาก Client/User ก่อนที่จะกำหนดให้กับ parameters (:????)
        $this->foodShopname = htmlspecialchars(strip_tags($this->foodShopname));
        $this->foodMeal = intval(htmlspecialchars(strip_tags($this->foodMeal)));
        $this->foodImage = htmlspecialchars(strip_tags($this->foodImage));
        $this->foodPay = intval(htmlspecialchars(strip_tags($this->foodPay)));
        $this->foodDate = htmlspecialchars(strip_tags($this->foodDate));
        $this->foodProvince = htmlspecialchars(strip_tags($this->foodProvince));
        $this->foodLat = htmlspecialchars(strip_tags($this->foodLat));
        $this->foodLng = htmlspecialchars(strip_tags($this->foodLng));
        $this->memId = intval(htmlspecialchars(strip_tags($this->memId)));

        //สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);

        //เอาที่ผ่านการตรวจแล้วไปกำหนดให้กับ parameters
        $stmt->bindParam(":foodShopname", $this->foodShopname);
        $stmt->bindParam(":foodMeal", $this->foodMeal);
        $stmt->bindParam(":foodImage", $this->foodImage);
        $stmt->bindParam(":foodPay", $this->foodPay);
        $stmt->bindParam(":foodDate", $this->foodDate);
        $stmt->bindParam(":foodProvince", $this->foodProvince);
        $stmt->bindParam(":foodLat", $this->foodLat);
        $stmt->bindParam(":foodLng", $this->foodLng);
        $stmt->bindParam(":memId", $this->memId);


        //สั่งให้ SQL ทำงาน และส่งผลลัพธ์ว่าเพิ่มข้อมูลสําเร็จหรือไม่
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //ฟังก์ชันแก้ไขข้อมูลบันทึกการกิน
    public function updateDiaryfood(){   
        $strSQL = "";
        if($this->foodImage == ""){
        $strSQL = "UPDATE diaryfood_tb SET `foodShopname` = :foodShopname, `foodMeal` = :foodMeal, `foodPay` = :foodPay, `foodDate` = :foodDate, `foodProvince` = :foodProvince, `foodLat` = :foodLat, `foodLng` = :foodLng, `memId` = :memId WHERE `foodId` = :foodId;";

    }else{$strSQL = "UPDATE diaryfood_tb SET `foodShopname` = :foodShopname, `foodMeal` = :foodMeal, `foodImage` = :foodImage, `foodPay` = :foodPay, `foodDate` = :foodDate, `foodProvince` = :foodProvince, `foodLat` = :foodLat, `foodLng` = :foodLng, `memId` = :memId WHERE `foodId` = :foodId;";
}
        
        //ตรวจสอบค่าที่ถูกส่งจาก Client/User ก่อนที่จะกำหนดให้กับ parameters (:????)
        $this->foodId = intval(htmlspecialchars(strip_tags($this->foodId)));
        $this->foodShopname = htmlspecialchars(strip_tags($this->foodShopname));
        $this->foodMeal = intval(htmlspecialchars(strip_tags($this->foodMeal)));
        if($this->foodImage != ""){$this->foodImage = htmlspecialchars(strip_tags($this->foodImage));}
        $this->foodPay = intval(htmlspecialchars(strip_tags($this->foodPay)));
        $this->foodDate = htmlspecialchars(strip_tags($this->foodDate));
        $this->foodProvince = htmlspecialchars(strip_tags($this->foodProvince));
        $this->foodLat = htmlspecialchars(strip_tags($this->foodLat));
        $this->foodLng = htmlspecialchars(strip_tags($this->foodLng));
        $this->memId = intval(htmlspecialchars(strip_tags($this->memId)));

        //สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);

        //เอาที่ผ่านการตรวจแล้วไปกำหนดให้กับ parameters
        $stmt->bindParam(":foodId", $this->foodId);
        $stmt->bindParam(":foodShopname", $this->foodShopname);
        $stmt->bindParam(":foodMeal", $this->foodMeal);
        if($this->foodImage != ""){$stmt->bindParam(":foodImage", $this->foodImage);}
        $stmt->bindParam(":foodPay", $this->foodPay);
        $stmt->bindParam(":foodDate", $this->foodDate);
        $stmt->bindParam(":foodProvince", $this->foodProvince);
        $stmt->bindParam(":foodLat", $this->foodLat);
        $stmt->bindParam(":foodLng", $this->foodLng);
        $stmt->bindParam(":memId", $this->memId);


        //สั่งให้ SQL ทำงาน และส่งผลลัพธ์ว่าเพิ่มข้อมูลสําเร็จหรือไม่
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //ฟังก์ชันลบข้อมูลบันทึกการกิน
    public function deleteDiaryfood() {
        $strSQL = "DELETE FROM diaryfood_tb WHERE `foodId` = :foodId;";
        $this->foodId = intval(htmlspecialchars(strip_tags($this->foodId)));
        $stmt = $this->connDB->prepare($strSQL);
        $stmt->bindParam(":foodId", $this->foodId);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
