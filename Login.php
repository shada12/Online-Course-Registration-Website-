
<?php
session_start();  // start PHP session! 
?> 
<?php
include "Lab5Common/Header.php"
?>


<?php

class Student {

    private $studentId;
    private $name;
    private $phoneNumber;
    private $passWord;

    function __construct($studentId, $name, $phoneNumber, $passWord) {
        $this->studentId = $studentId;
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
        $this->passWord = $passWord;
    }

}

$studentId = "";
$passWord = "";


$studentId = $_POST["studentId"];
$passWord = $_POST["passWord"];
$_SESSION["studentId"] = $studentId;
$_SESSION["passWord"]=$passWord;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $studentId = trim($studentId);
    $passWord = trim($passWord);


    $valid = false;
    $_SESSION["valid"] = $valid;

    $studentIdError = $passWordError = "";

    function ValidateStudentId($studentId) {

        if (empty($studentId)) {

            return "Student ID field can not be blank";
        } else {

            return "";
        }
    }

    function ValidatePassword($passWord) {
        if (empty($passWord)) {

            return "Password field can not be blank";
        }
    }

    $studentIdError = ValidateStudentId($studentId);
    $passWordError = ValidatePassword($passWord);

    $_SESSION["valid"] = $valid;


    if ($studentIdError == "" && $passWordError == "") {


        $valid = true;
        $_SESSION["valid"] = $valid;


        $myPdo = new PDO("mysql:host=localhost;dbname=CST8257;port=3306;charset=utf8",
                "PHPSCRIPT",
                "1234");

        function getUserById($studentId, $passWord, $myPdo) {


            $sql = "SELECT StudentId , Password FROM Student WHERE StudentId = :studentId && Password = :passWord";

            $pStmt = $myPdo->prepare($sql);
            $pStmt->execute(['studentId' => $studentId,
                'passWord' => $passWord]);

            $row = $pStmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return new Student($row['StudentId'], $row['Name'], $row['Phone'], $row['Password']);
            } else {
                return null;
            }
        }

        if (getUserById($studentId, $passWord, $myPdo) != null) {


            header("Location: http://localhost:8080/CST8257Lab5/CurrentRegistration.php");
        } else {

            $studentIdError = "No match for student with these user name and password";
        }
    }
}//post
?>

<form  id="myForm" method ="post" action="Login.php">

    <h1>Log In</h1>
    <p>You need to <a href="NewUser.php" style='color:blue'>sign up </a>if you a new user</p>



    <table id="table">
        <tr>
            <td>Student ID: </td><td><input type = "text" id="studentID" name ="studentId" value="<?php print("$studentId") ?>" />
                <span class="error"  ><?php print($studentIdError); ?></span></td>
        </tr>

        <tr>
            <td>Password:</td><td><input type = "text" id="password" name = "passWord" value="<?php print($passWord) ?>"/>
                <span class="error"><?php print($passWordError) ?></span></td>
        </tr>

    </table>
    <br />
    <br />

    <br/><br/>
    <input class="button" type = "submit" value = "Submit" />
    <input class="button" type="button" onclick= "clearform()" value="Clear">


</form>

<script>
    function clearform()
    {

    document.getElementById("studentID").value = "";   
    document.getElementById("password").value = "";


    }
</script>
<?php include "Lab5Common/Footer.php" ?>

