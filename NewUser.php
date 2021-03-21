
<?php
session_start();  // start PHP session! 
?> 

<?php include "Lab5Common/Header.php" ?>


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
$name = "";
$phoneNumber = "";
$passWord = "";
$passWordAgain = "";

$studentId = $_POST["studentId"];
$name = $_POST["name"];
$phoneNumber = $_POST["phoneNumber"];
$passWord = $_POST["passWord"];
$passWordAgain = $_POST["passWordAgain"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $studentId = trim($studentId);
    $name = trim($name);
    $phoneNumber = trim($phoneNumber);
    $passWord = trim($passWord);
    $passWordAgain = trim($passWordAgain);

    $valid = false;


    $studentIdError = $nameError = $phoneNumberError = $passWordError = $passWordAgainError = "";

    function ValidateStudentId($studentId) {

        if (empty($studentId)) {

            return "Student ID field can not be blank";
        } else {

            return "";
        }
    }

    function ValidateName($name) {
        if (empty($name)) {

            return "Name field can not be blank";
        } else {
            return "";
        }
    }

    function ValidatePhone($phone) {

        if (empty($phone)) {

            return "Phone number field can not be blank";
        } else {

            $phoneNumberRegex = "/^[2-9][0-9]{2}\-[2-9][0-9]{2}\-[0-9]{4}$/";
            if (!preg_match($phoneNumberRegex, $phone)) {

                return "Incorrect Phone Number";
            }
        }
    }

    function ValidatePassword($passWord) {
        if (empty($passWord)) {

            return "Password field can not be blank";
        } else {

            $passwordRegex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/";

            if (!preg_match($passwordRegex, $passWord)) {

                return "Password is at least 6 characters of at least one number, and one uppercase and lowercase letter ";
            }
        }
    }

    function ValidatePasswordAgain($passWordAgain, $passWord) {

        if (empty($passWordAgain)) {

            return "Password Again field can not be blank";
        }


        if (strcmp($passWordAgain, $passWord) != 0) {


            return "Incompatible Password";
        }
    }

    $studentIdError = ValidateStudentId($studentId);
    $nameError = ValidateName($name);
    $phoneNumberError = ValidatePhone($phoneNumber);
    $passWordError = ValidatePassword($passWord);
    $passWordAgainError = ValidatePasswordAgain($passWordAgain, $passWord);

    $_SESSION["valid"] = $valid;
    $_SESSION["studentId"] = $_POST["studentId"];
    $_SESSION["name"] = $_POST["name"];
    $_SESSION["phoneNumber"] = $_POST["phoneNumber"];
    $_SESSION["passWord"] = $_POST["passWord"];


    if ($nameError == "" && $phoneNumberError == "" && $studentIdError == "" && $passWordError == "" && $passWordAgainError == "") {

        $valid = true;
        $_SESSION["valid"] = $valid;

        $myPdo = new PDO("mysql:host=localhost;dbname=CST8257;port=3306;charset=utf8",
                "PHPSCRIPT",
                "1234");

        function getUserById($studentId, $myPdo) {


            $sql = "SELECT StudentId , Name FROM Student WHERE StudentId = :studentId";

            $pStmt = $myPdo->prepare($sql);
            $pStmt->execute(['studentId' => $studentId]);

            $row = $pStmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return new Student($row['StudentId'], $row['Name'], $row['Phone'], $row['Password']);
            } else {
                return null;
            }
        }

        if (getUserById($studentId, $myPdo) == null) {


            $sql = "INSERT INTO Student  (StudentId, Name, Phone,Password ) VALUES( :studentId, :name, :phoneNumber, :passWord)";

            $pStmt = $myPdo->prepare($sql);

            $pStmt->execute(['studentId' => $studentId,
                'name' => $name,
                'phoneNumber' => $phoneNumber,
                'passWord' => $passWord]);

            $error = $pStmt->errorInfo();

            header("Location: http://localhost:8080/CST8257Lab5/CourseSelection.php");
        } else {

            $studentIdError = "A student with this ID has already signed up";
        }
    }
}//post
?>

<form  id="myForm" method ="post" action="NewUser.php">

    <h1>Sign Up</h1>

    <p>All fields are required</p> 

    <table id="table">
        <tr>
            <td>Student ID: </td><td><input type = "text" id="studentID" name ="studentId" value="<?php print("$studentId") ?>" />
                <span class="error"  ><?php print($studentIdError); ?></span></td>
        </tr>
        <tr>
            <td>Name:</td><td><input type = "text"  id="name" name = "name" value="<?php print($name) ?>" />
                <span class="error" ><?php print($nameError) ?></span></td>
        </tr>

        <tr>
            <td>Phone Number:<br/>(nnn-nnn-nnnn)</td><td><input type = "text" id="phone" name = "phoneNumber" value="<?php print($phoneNumber) ?>" />
                <span class="error"><?php print($phoneNumberError) ?></span></td>
        </tr>
        <tr>
            <td>Password:</td><td><input type = "text" id="password" name = "passWord" value="<?php print($passWord) ?>"/>
                <span class="error"><?php print($passWordError) ?></span></td>
        </tr>

        <tr>
            <td>Password Again:</td><td><input type = "text" id="passwordagain" name = "passWordAgain" value="<?php print($passWordAgain) ?>"/>
                <span class="error"><?php print($passWordAgainError) ?></span></td>
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
    document.getElementById("name").value = "";  
    document.getElementById("phone").value = "";    
    document.getElementById("password").value = "";
    document.getElementById("passwordagain").value = "";
    document.getElementsByClassName("error").value = "";

    }
</script>
<?php include "Lab5Common/Footer.php" ?>

