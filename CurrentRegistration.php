<?php
session_start();  // start PHP session! 
?>  
<?php
$validLogin = true;
$_Session["validLogin"] = $validLogin;

include "Lab5Common/Header.php"
?>

<?php
$valid = $_SESSION["valid"];

if ($valid == false) {

    header("Location: http://localhost:8080/CST8257Lab5/Login.php");
    exit();
}
?>

<?php

/*class Student {

    private $StudentId;
    private $Name;
    private $Phone;
    private $Password;

    function __construct($StudentId, $Name, $Phone, $Password) {
        $this->StudentId = $StudentId;
        $this->Name = $Name;
        $this->Phone = $Phone;
        $this->Password = $Password;
    }

    function getStudentId() {
        return $this->StudentId;
    }

    function getName() {
        return $this->Name;
    }

    function getPhone() {
        return $this->Phone;
    }

    function getPassword() {
        return $this->Password;
    }

    function setStudentId($StudentId) {
        $this->StudentId = $StudentId;
    }

    function setName($Name) {
        $this->Name = $Name;
    }

    function setPhone($Phone) {
        $this->Phone = $Phone;
    }

    function setPassword($Password) {
        $this->Password = $Password;
    }

}

class Semester {

    private $SemesterCode;
    private $Term;
    private $Year;

    function __construct($SemesterCode, $Term, $Year) {
        $this->SemesterCode = $SemesterCode;
        $this->Term = $Term;
        $this->Year = $Year;
    }

    function getSemesterCode() {
        return $this->SemesterCode;
    }

    function getTerm() {
        return $this->Term;
    }

    function getYear() {
        return $this->Year;
    }

    function setSemesterCode($SemesterCode) {
        $this->SemesterCode = $SemesterCode;
    }

    function setTerm($Term) {
        $this->Term = $Term;
    }

    function setYear($Year) {
        $this->Year = $Year;
    }

}

class Course {

    private $CourseCode;
    private $Title;
    private $WeeklyHours;

    function __construct($CourseCode, $Title, $WeeklyHours) {
        $this->CourseCode = $CourseCode;
        $this->Title = $Title;
        $this->WeeklyHours = $WeeklyHours;
    }

    function getCourseCode() {
        return $this->CourseCode;
    }

    function getTitle() {
        return $this->Title;
    }

    function getWeeklyHours() {
        return $this->WeeklyHours;
    }

    function setCourseCode($CourseCode) {
        $this->CourseCode = $CourseCode;
    }

    function setTitle($Title) {
        $this->Title = $Title;
    }

    function setWeeklyHours($WeeklyHours) {
        $this->WeeklyHours = $WeeklyHours;
    }

}

class CourseOffer {

    function __construct($CourseCode, $SemesterCode) {
        $this->CourseCode = $CourseCode;
        $this->SemesterCode = $SemesterCode;
    }

    function getCourseCode() {
        return $this->CourseCode;
    }

    function getSemesterCode() {
        return $this->SemesterCode;
    }

    function setCourseCode($CourseCode) {
        $this->CourseCode = $CourseCode;
    }

    function setSemesterCode($SemesterCode) {
        $this->SemesterCode = $SemesterCode;
    }

    private $CourseCode;
    private $SemesterCode;

}

class Registration {

    private $StudentId;
    private $CourseCode;
    private $SemesterCode;

    function getStudentId() {
        return $this->StudentId;
    }

    function getCourseCode() {
        return $this->CourseCode;
    }

    function getSemesterCode() {
        return $this->SemesterCode;
    }

    function setStudentId($StudentId) {
        $this->StudentId = $StudentId;
    }

    function setCourseCode($CourseCode) {
        $this->CourseCode = $CourseCode;
    }

    function setSemesterCode($SemesterCode) {
        $this->SemesterCode = $SemesterCode;
    }

    function __construct($StudentId, $CourseCode, $SemesterCode) {
        $this->StudentId = $StudentId;
        $this->CourseCode = $CourseCode;
        $this->SemesterCode = $SemesterCode;
    }

}
 */


$studentId = $_SESSION["studentId"];
$passWord = $_SESSION["passWord"];
$name = $_SESSION["name"];
$phoneNumber = $_SESSION["phoneNumber"];

if ($_SESSION["studentId"] == null){ 
              
        exit(header('Location: Login.php'));
    }

//echo $studentId;
$selectedCourses = $_POST["selectCourses"];

$registeredCourses = array();

$studentName = "";


$myPdo = new PDO("mysql:host=localhost;dbname=CST8257;port=3306;charset=utf8",
        "PHPSCRIPT",
        "1234");

if (isset($_POST["selectCourses"])) {
    foreach ($_POST["selectCourses"] as $code) {

        $sql = "DELETE FROM Registration WHERE CourseCode =  :courseCode And StudentId = :studentId";

        $stmt = $myPdo->prepare($sql);
        $stmt->execute(['courseCode' => $code,
                        'studentId'=>$studentId  ]);
    }
}

////////////////////////////////


$sqlQ = "SELECT  Year, Term, Course.CourseCode Code, Title,  WeeklyHours "
        . "FROM Course INNER JOIN Registration ON Course.CourseCode = Registration.CourseCode "
        . "INNER JOIN Semester ON Semester.SemesterCode= Registration.SemesterCode "
        . " WHERE Registration.StudentId= :studentId";



$pStmt = $myPdo->prepare($sqlQ);

$pStmt->execute(['studentId' => $studentId]);

$registeredCourses = $pStmt->fetchAll();

////////////
//$sql = "SELECT Course.CourseCode Code, Title,  WeeklyHours "
//     . "FROM Course INNER JOIN Registration ON Course.CourseCode = Registration.CourseCode "
//    . " WHERE Registration.StudentId= :studentId";
//$pStmt = $myPdo->prepare($sql);
//$pStmt->execute(['studentId' => $studentId]);
// to get student Name from DB using student ID

$sqlStudent = "SELECT * From Student where StudentId = :studentId";
$pSt = $myPdo->prepare($sqlStudent);
$pSt->execute(['studentId' => $studentId]);

$rows = $pSt->fetch(PDO::FETCH_ASSOC);

$studentName = $rows['Name'];
?>

<form  id="myForm" method ="post" action="CurrentRegistration.php">

    <h1 align="center">Current Registration</h1>
    <br/>
    <p>Hello <?php print($studentName) ?> (not you? change user <a href="Login.php" style='color:blue'>here</a>)
        , the following are your current registrations</p>


    <br/>
 
    <table  class=" table">
        <tr><td> Year </td><td> Term </td><td> Course Code </td><td> Course Title </td><td> Hours </td><td> Select </td></tr>


        <?php
        $totalHoursFall2017 = 0;
        $totalHoursSummer2017 = 0;
        $totalHoursWinter2017 = 0;
        $totalHoursFall2018 = 0;
        $totalHoursSummer2018 = 0;
        $totalHoursWinter2018 = 0;
        $totalHoursFall2019 = 0;
        $totalHoursSummer2019 = 0;
        $totalHoursWinter2019 = 0;
        ?>

        <?php
        foreach ($registeredCourses as $row) {
            if ($row["Year"] == "2017" && $row["Term"] == "Fall") {
                ?>
                <tr><td><?php print($row['Year']) ?></td>
                    <td><?php print($row['Term']) ?></td>
                    <td><?php print($row['Code']) ?></td>
                    <td><?php print($row['Title']) ?></td>
                    <td><?php print($row['WeeklyHours']) ?></td>
                    <td> <input type="checkbox" name="selectCourses[]" value='<?php print($row['Code']) ?>'></td></tr>

                <?php
                $totalHoursFall2017 += $row['WeeklyHours'];
            }
        }

        if ($totalHoursFall2017 > 0) {
            echo"<td></td><td></td><td></td><th>Total Weekly Hours</th><td>" . $totalHoursFall2017 . "</td>";
        }

        foreach ($registeredCourses as $row) {
            if ($row["Year"] == "2017" && $row["Term"] == "Summer") {
                ?>
                <tr><td><?php print($row['Year']) ?></td>
                    <td><?php print($row['Term']) ?></td>
                    <td><?php print($row['Code']) ?></td>
                    <td><?php print($row['Title']) ?></td>
                    <td><?php print($row['WeeklyHours']) ?></td>
                    <td> <input type="checkbox" name="selectCourses[]" value='<?php print($row['Code']) ?>'></td></tr>

                <?php
                $totalHoursSummer2017 += $row['WeeklyHours'];
            }
        }
        if ($totalHoursSummer2017 > 0) {
            echo"<td></td><td></td><td></td><th>Total Weekly Hours</th><td>" . $totalHoursSummer2017 . "</td>";
        }
        foreach ($registeredCourses as $row) {
            if ($row["Year"] == "2017" && $row["Term"] == "Winter") {
                ?>
                <tr><td><?php print($row['Year']) ?></td>
                    <td><?php print($row['Term']) ?></td>
                    <td><?php print($row['Code']) ?></td>
                    <td><?php print($row['Title']) ?></td>
                    <td><?php print($row['WeeklyHours']) ?></td>
                    <td> <input type="checkbox" name="selectCourses[]" value='<?php print($row['Code']) ?>'></td></tr>

                <?php
                $totalHoursWinter2017 += $row['WeeklyHours'];
            }
        }

        if ($totalHoursWinter2017 > 0) {
            echo"<td></td><td></td><td></td><th>Total Weekly Hours</th><td>" . $totalHoursWinter2017 . "</td>";
        }
        foreach ($registeredCourses as $row) {
            if ($row["Year"] == "2018" && $row["Term"] == "Fall") {
                ?>
                <tr><td><?php print($row['Year']) ?></td>
                    <td><?php print($row['Term']) ?></td>
                    <td><?php print($row['Code']) ?></td>
                    <td><?php print($row['Title']) ?></td>
                    <td><?php print($row['WeeklyHours']) ?></td>
                    <td> <input type="checkbox" name="selectCourses[]" value='<?php print($row['Code']) ?>'></td></tr>

                <?php
                $totalHoursFall2018 += $row['WeeklyHours'];
            }
        }
        if ($totalHoursFall2018 > 0) {
            echo"<td></td><td></td><td></td><th>Total Weekly Hours</th><td>" . $totalHoursFall2018 . "</td>";
        }
        foreach ($registeredCourses as $row) {

            if ($row["Year"] == "2018" && $row["Term"] == "Summer") {
                ?>
                <tr><td><?php print($row['Year']) ?></td>
                    <td><?php print($row['Term']) ?></td>
                    <td><?php print($row['Code']) ?></td>
                    <td><?php print($row['Title']) ?></td>
                    <td><?php print($row['WeeklyHours']) ?></td>
                    <td> <input type="checkbox" name="selectCourses[]" value='<?php print($row['Code']) ?>'></td></tr>

                <?php
                $totalHoursSummer2018 += $row['WeeklyHours'];
            }
        }

        if ($totalHoursSummer2018 > 0) {
            echo"<td></td><td></td><td></td><th>Total Weekly Hours</th><td>" . $totalHoursSummer2018 . "</td>";
        }
        foreach ($registeredCourses as $row) {
            if ($row["Year"] == "2018" && $row["Term"] == "Winter") {
                ?>
                <tr><td><?php print($row['Year']) ?></td>
                    <td><?php print($row['Term']) ?></td>
                    <td><?php print($row['Code']) ?></td>
                    <td><?php print($row['Title']) ?></td>
                    <td><?php print($row['WeeklyHours']) ?></td>
                    <td> <input type="checkbox" name="selectCourses[]" value='<?php print($row['Code']) ?>'></td></tr>

                <?php
                $totalHoursWinter2018 += $row['WeeklyHours'];
            }
        }

        if ($totalHoursWinter2018 > 0) {
            echo"<td></td><td></td><td></td><th>Total Weekly Hours</th><td>" . $totalHoursWinter2018 . "</td>";
        }
        foreach ($registeredCourses as $row) {
            if ($row["Year"] == "2019" && $row["Term"] == "Fall") {
                $counter++;
                ?>
                <tr><td><?php print($row['Year']) ?></td>
                    <td><?php print($row['Term']) ?></td>
                    <td><?php print($row['Code']) ?></td>
                    <td><?php print($row['Title']) ?></td>
                    <td><?php print($row['WeeklyHours']) ?></td>
                    <td> <input type="checkbox" name="selectCourses[]" value='<?php print($row['Code']) ?>'></td></tr>

                <?php
                $totalHoursFall2019 += $row['WeeklyHours'];
            }
        }
        if ($totalHoursFall2019 > 0) {
            echo"<td></td><td></td><td></td><th>Total Weekly Hours</th><td>" . $totalHoursFall2019 . "</td>";
        }
        foreach ($registeredCourses as $row) {


            if ($row["Year"] == "2019" && $row["Term"] == "Summer") {
                ?>
                <tr><td><?php print($row['Year']) ?></td>
                    <td><?php print($row['Term']) ?></td>
                    <td><?php print($row['Code']) ?></td>
                    <td><?php print($row['Title']) ?></td>
                    <td><?php print($row['WeeklyHours']) ?></td>
                    <td> <input type="checkbox" name="selectCourses[]" value='<?php print($row['Code']) ?>'></td></tr>

                <?php
                $totalHoursSummer2019 += $row['WeeklyHours'];
            }
        }
        if ($totalHoursSummer2019 > 0) {
            echo"<td></td><td></td><td></td><th>Total Weekly Hours</th><td>" . $totalHoursSummer2019 . "</td>";
        }
        foreach ($registeredCourses as $row) {
            if ($row["Year"] == "2019" && $row["Term"] == "Winter") {
                ?>
                <tr><td><?php print($row['Year']) ?></td>
                    <td><?php print($row['Term']) ?></td>
                    <td><?php print($row['Code']) ?></td>
                    <td><?php print($row['Title']) ?></td>
                    <td><?php print($row['WeeklyHours']) ?></td>
                    <td> <input type="checkbox" name="selectCourses[]" value='<?php print($row['Code']) ?>'></td></tr>

                <?php
                $totalHoursWinter2019 += $row['WeeklyHours'];
            }
        }
        if ($totalHoursWinter2019 > 0) {
            echo"<td></td><td></td><td></td><th>Total Weekly Hours</th><td>" . $totalHoursWinter2019 . "</td>";
        }
        ?>     
    </table>
      

    <br/><br/>

    <div align="right">
        <input class="button" type = "submit"  onclick= "return checkDelete()" value = "Delete Selected" />
        <input class="button" type="button" value="Clear">


    </div>

</form>
<script language="JavaScript" type="text/javascript">
    function checkDelete(){
    return confirm("The selected registration will be deleted!");
    }
</script>
<script>

    function myFunction() {

    var form = document.getElementById("myForm");
    form.submit();

    }

</script>

<?php include "Lab5Common/Footer.php" ?>
