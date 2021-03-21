
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

    header("Location: http://localhost:8080/CST8257Lab5/NewUser.php");
    exit();
}
?> 
<?php

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

$studentId = $_SESSION["studentId"];
$name = $_SESSION["name"];
$phoneNumber = $_SESSION["phoneNumber"];
$passWord = $_SESSION["passWord"];


if ($_SESSION["studentId"] == null) {

    header('Location: Login.php');
    exit();
}

//extract($_POST);

$selectedSemesterCode = $_POST["semester"];
$selectCourses = $_POST["selectCourses"];
$courses = array();
$SelectedCourses = array();
$selectError = "";


// Access DB
$myPdo = new PDO("mysql:host=localhost;dbname=CST8257;port=3306;charset=utf8",
        "PHPSCRIPT",
        "1234");

///////////// 1- select all semesters to show them in used drop list

$sqlSemester = "SELECT * From Semester";

$q = $myPdo->query($sqlSemester)->fetchAll();

//////////////////////////////////////////////////////////////////

$selectedHours = 0;

if (isset($_POST["selectCourses"])) {

    foreach ($_POST["selectCourses"] as $courseCode) {

        $sqlCors = "SELECT * From Course WHERE CourseCode= :selectCourses";
        $pStm = $myPdo->prepare($sqlCors);
        $pStm->execute(['selectCourses' => $courseCode]);
        $row = $pStm->fetch(PDO::FETCH_ASSOC);

        if ($row) {

            $course = new Course($row['CourseCode'], $row['Title'], $row['WeeklyHours']);
            $SelectedCourses[] = $course;

            $selectedHours += $row['WeeklyHours'];
        }
    }

    $counter = 0;
    if ($selectedHours > 16) {
        $selectError = "Your selection exceed the max weekly hours";
    } else {

        $counter++;
        /////////////////////////////         save registered courses in DB       //////////////////////////////////////
        foreach ($SelectedCourses as $course) {

            $sql = "INSERT INTO Registration (StudentId, CourseCode,SemesterCode) VALUES( :studentId, :courseCode, :semester)";

            $pStmtt = $myPdo->prepare($sql);

            $pStmtt->execute(['studentId' => $studentId,
                'courseCode' => $course->getCourseCode(),
                'semester' => $selectedSemesterCode]);

            $error = $pStmtt->errorInfo();
        }
    }
}

if (!isset($_POST["selectCourses"])) {

    $selectError = "you need select at least one course!";
}

/////////// 2- Select courses for selected semester (exclude courses the use has already registered) then store them in array to display them for the user

$sql = "SELECT Course.CourseCode Code, Title,  WeeklyHours "
        . "FROM Course INNER JOIN CourseOffer ON Course.CourseCode = CourseOffer.CourseCode "
        . "WHERE CourseOffer.SemesterCode = :semester AND Course.CourseCode NOT IN (SELECT CourseCode From Registration Where Registration.StudentId = :studentId ) ";

$pStmt = $myPdo->prepare($sql);

$pStmt->execute(['semester' => $selectedSemesterCode,
    'studentId' => $studentId]);


foreach ($pStmt as $row) {
    $course = new Course($row['Code'], $row['Title'], $row['WeeklyHours']);
    $courses[] = $course;
}

//////////////////////////////////////////////
//$pStmt = $myPdo->prepare($sqlQ);

//$pStmt->execute(['studentId' => $studentId]);

//$registeredCourses = $pStmt->fetchAll();
//////////////////////////////////////////////////////////////////////
?> 
<form  id="myForm" method ="post" action="CourseSelection.php">

    <h1 align="center">Course Selection</h1>
    <br/>
    <p>Welcome <?php print($name) ?> (not you? change user <a href="Login.php" style='color:blue'>here</a>)
        <br/> You have registered  <?php
        if ($counter > 0) {
            print($selectedHours);
        } else {
            print("0");
        }
        ?>  hours for the selected semester
        <br/> You can register <?php
        if ($counter > 0) {
            print( 16 - $selectedHours);
        } else {
            print("16");
        }
        ?>  more hours of course(s)for the semester
        <br/>Please note that the courses you have registered will not be displayed in the list
    </p>
    <br/><td>
        <p align="right">
            <select name="semester" id="mySelect" onchange="myFunction()">
                <option value="-1" >Select Semester</option> 
                <?php
                foreach ($q as $row) {
                    if ($_POST['semester'] == $row['SemesterCode']) {
                        $selected = 'selected';
                    } else {
                        $selected = "";
                    }
                    ?>

                    <option value= '<?php print( $row['SemesterCode']) ?>' <?php echo $selected ?> ><?php print($row['Term'] . " " . $row['Year']) ?></option>
                    <?php
                }
                ?>
            </select> 
        </p>
        <br/>
        
        <span class="error" ><?php print($selectError) ?></span>


        <table class="table">
            <tr><td> Code </td><td> Course Title </td><td> Hours </td><td> Select </td></tr>
            <?php
            foreach ($courses as $course) {
                ?>
                <tr><td><?php print($course->getCourseCode()) ?></td>
                    <td> <?php print($course->getTitle()) ?> </td>
                    <td> <?php print( $course->getWeeklyHours()) ?> </td>
                    <td> <input type="checkbox" name="selectCourses[]" value='<?php print( $course->getCourseCode()) ?>'></td></tr>

            <?php }
            ?>
        </table>
        <br/><br/>

        <div align="right">
            <input class="button" type = "submit" value = "Submit" />
            <input class="button" type="button" value="Clear">
        </div>

</form>

<script>

    function myFunction() {

    var form = document.getElementById("myForm");
    form.submit();

    }

</script>

<?php include "Lab5Common/Footer.php" ?>
 