<?php

class Member
{
    //ตัวแปรที่ใช้เก็บการติดต่อฐานข้อมูล
    private $connDB;

    //ตัวแปรที่ทำงานคู่กับคอลัมน์(ฟิวล์)ในตาราง
    public $memId;
    public $memFullname;
    public $memEmail;
    public $memUsername;
    public $memPassword;
    public $memAge;
    public $memImage;

    //ตัวแปรสารพัดประโยชน์
    public $message;

    //constructor
    public function __construct($connDB)
    {
        $this->connDB = $connDB;
    }
    //----------------------------------------------
    //ฟังก์ชันการทำงานที่ล้อกับส่วนของ APIs

    //ฟังชันก์ตรวจสอบชื่อผู้ใช้และรหัสผ่าน
    public function checkLoginCaseInsensitive()
    {
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

    public function checkLoginCaseSensitive()
{
    // คำสั่ง SQL ที่ใช้ในการเปรียบเทียบโดยตรง (case-sensitive)
    $strSQL = "SELECT * FROM member_tb WHERE BINARY memUsername = :memUsername AND BINARY memPassword = :memPassword";

    // ตรวจสอบค่าที่ถูกส่งจาก Client/User ก่อนที่จะกำหนดให้กับ parameters
    $this->memUsername = htmlspecialchars(strip_tags($this->memUsername));
    $this->memPassword = htmlspecialchars(strip_tags($this->memPassword));

    // สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
    $stmt = $this->connDB->prepare($strSQL);

    // เอาที่ผ่านการตรวจแล้วไปกำหนดให้กับ parameters
    $stmt->bindParam(":memUsername", $this->memUsername);
    $stmt->bindParam(":memPassword", $this->memPassword);

    // สั่งให้ SQL ทำงาน
    $stmt->execute();

    // ส่งค่าผลการทำงานกลับไปยังจุดเรียกใช้ฟังก์ชันนี้
    return $stmt;
}


    //ฟังก์ชันเพิ่มข้อมูลผู้ใช้ใหม่
    public function registerMemberNoncheck()
    {
        //ตัวแปรเก็บคำสั่ง SQL
        $strSQL = "INSERT INTO member_tb (`memFullname`, `memEmail`, `memUsername`, `memPassword`, `memAge` , `memImage`) VALUES (:memFullname, :memEmail, :memUsername, :memPassword, :memAge, :memImage);";

        //ตรวจสอบค่าที่ถูกส่งจาก Client/User ก่อนที่จะกำหนดให้กับ parameters (:????)
        $this->memFullname = htmlspecialchars(strip_tags($this->memFullname));
        $this->memEmail = htmlspecialchars(strip_tags($this->memEmail));
        $this->memUsername = htmlspecialchars(strip_tags($this->memUsername));
        $this->memPassword = htmlspecialchars(strip_tags($this->memPassword));
        $this->memAge = intval(htmlspecialchars(strip_tags($this->memAge)));
        $this->memImage = htmlspecialchars(strip_tags($this->memImage));

        //สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);

        //เอาที่ผ่านการตรวจแล้วไปกำหนดให้กับ parameters
        $stmt->bindParam(":memFullname", $this->memFullname);
        $stmt->bindParam(":memEmail", $this->memEmail);
        $stmt->bindParam(":memUsername", $this->memUsername);
        $stmt->bindParam(":memPassword", $this->memPassword);
        $stmt->bindParam(":memAge", $this->memAge);
        $stmt->bindParam(":memImage", $this->memImage);

        //สั่งให้ SQL ทำงาน และส่งผลลัพธ์ว่าเพิ่มข้อมูลสําเร็จหรือไม่
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function registerMember()
{
    // ตรวจสอบว่า ชื่อ ซ้ำหรือไม่ (case-insensitive)
    $strSQL = "SELECT * FROM member_tb WHERE LOWER(memFullname) = LOWER(:memFullname)";
    $stmt = $this->connDB->prepare($strSQL);
    $stmt->bindParam(":memFullname", $this->memFullname);
    $stmt->execute();

    // ถ้าพบข้อมูลแสดงว่า ชื่อ ซ้ำ
    if ($stmt->rowCount() > 0) {
        return 3; // บอกว่ามี ชื่อ ซ้ำ
    }

    // ตรวจสอบว่า username ซ้ำหรือไม่ (case-insensitive)
    $strSQL = "SELECT * FROM member_tb WHERE LOWER(memUsername) = LOWER(:memUsername)";
    $stmt = $this->connDB->prepare($strSQL);
    $stmt->bindParam(":memUsername", $this->memUsername);
    $stmt->execute();

    // ถ้าพบข้อมูลแสดงว่า username ซ้ำ
    if ($stmt->rowCount() > 0) {
        return 2; // บอกว่ามี username ซ้ำ
    }

    // ถ้าไม่พบข้อมูล ให้ดำเนินการเพิ่มสมาชิกใหม่
    $strSQL = "INSERT INTO member_tb (`memFullname`, `memEmail`, `memUsername`, `memPassword`, `memAge`, `memImage`) 
               VALUES (:memFullname, :memEmail, :memUsername, :memPassword, :memAge, :memImage)";

    // ตรวจสอบค่าที่ถูกส่งจาก Client/User ก่อนที่จะกำหนดให้กับ parameters
    $this->memFullname = htmlspecialchars(strip_tags($this->memFullname));
    $this->memEmail = htmlspecialchars(strip_tags($this->memEmail));
    $this->memUsername = htmlspecialchars(strip_tags($this->memUsername));
    $this->memPassword = htmlspecialchars(strip_tags($this->memPassword));
    $this->memAge = intval(htmlspecialchars(strip_tags($this->memAge)));
    $this->memImage = htmlspecialchars(strip_tags($this->memImage));

    // สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
    $stmt = $this->connDB->prepare($strSQL);

    // เอาที่ผ่านการตรวจแล้วไปกำหนดให้กับ parameters
    $stmt->bindParam(":memFullname", $this->memFullname);
    $stmt->bindParam(":memEmail", $this->memEmail);
    $stmt->bindParam(":memUsername", $this->memUsername);
    $stmt->bindParam(":memPassword", $this->memPassword);
    $stmt->bindParam(":memAge", $this->memAge);
    $stmt->bindParam(":memImage", $this->memImage);

    // สั่งให้ SQL ทำงาน และส่งผลลัพธ์ว่าเพิ่มข้อมูลสำเร็จหรือไม่
    if ($stmt->execute()) {
        return 1; // เพิ่มสมาชิกสำเร็จ
    } else {
        return 0; // เพิ่มสมาชิกไม่สำเร็จ
    }
}


    //ฟังก์ชันแก้ไขข้อมูลผู้ใช้
    public function updateMember()
    {
        //ตัวแปรเก็บคำสั่ง SQL
        $strSQL = "";
        if($this->memImage == ""){
            $strSQL = "UPDATE member_tb SET `memEmail` = :memEmail, `memUsername` = :memUsername, `memPassword` = :memPassword, `memAge` = :memAge WHERE `memId` = :memId";
        }else{
            $strSQL = "UPDATE member_tb SET `memEmail` = :memEmail, `memUsername` = :memUsername, `memPassword` = :memPassword, `memAge` = :memAge, `memImage` = :memImage WHERE `memId` = :memId";
        }
    
        //ตรวจสอบค่าที่ถูกส่งจาก Client/User ก่อนที่จะกำหนดให้กับ parameters (:????)
        $this->memId = intval(htmlspecialchars(strip_tags($this->memId)));
        $this->memEmail = htmlspecialchars(strip_tags($this->memEmail));
        $this->memUsername = htmlspecialchars(strip_tags($this->memUsername));
        $this->memPassword = htmlspecialchars(strip_tags($this->memPassword));
        $this->memAge = intval(htmlspecialchars(strip_tags($this->memAge)));
        if($this->memImage != ""){$this->memImage = htmlspecialchars(strip_tags($this->memImage));}

        //สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);

        //เอาที่ผ่านการตรวจแล้วไปกำหนดให้กับ parameters
        $stmt->bindParam(":memId", $this->memId);
        $stmt->bindParam(":memEmail", $this->memEmail);
        $stmt->bindParam(":memUsername", $this->memUsername);
        $stmt->bindParam(":memPassword", $this->memPassword);
        $stmt->bindParam(":memAge", $this->memAge);
        if($this->memImage != ""){$stmt->bindParam(":memImage", $this->memImage);}

        //สั่งให้ SQL ทำงาน และส่งผลลัพธ์ว่าเพิ่มข้อมูลสําเร็จหรือไม่
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
}
