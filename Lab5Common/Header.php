    
<?php
session_start();  // start PHP session! 
// $validLogin = $_Session["validLogin"];
?>  
<!DOCTYPE html>
<html lang="en" style="position: relative; min-height: 100%;">
    <head>
        <title>Online Course Registration</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="AlgCommon/Contents/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="AlgCommon/Contents/AlgCss/Site.css" rel="stylesheet" type="text/css"/>

        <link href="LabContents/Site.css" rel="stylesheet" type="text/css"/>

        <style>
            table {
                border-collapse: collapse;
            }
            td        { padding-top: 8px;
                        padding-bottom: 8px;
                        padding-left: 10px;
                        padding-right: 10px 
            } 

        </style>
    </head>
    <body style="padding-top: 50px; margin-bottom: 60px;">
        <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" 
                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a class="navbar-brand" style="padding: 10px" href="http://www.algonquincollege.com">
                                <img src="AlgCommon/Contents/img/AC.png" 
                                     alt="Algonquin College" style="max-width:100%; max-height:100%;"/>
                            </a></li>
                        <li class="active"><a href="Index.php">Home </a></li>
                        <li><a href="CourseSelection.php">Course Selection</a></li>
                        <li><a href="CurrentRegistration.php">Current Registration</a></li>


                        <?php
                        $validLogin = $_Session["validLogin"];

                        if ($validLogin == true) {
                            ?>
                            <li><a href="Logout.php">Log out</a></li>;
                            <?php
                        } else {
                            ?>
                            <li><a href="Login.php">Log in</a></li> ;
                        <?php }
                        ?>
                    </ul>
                </div>
            </div>  
        </nav>
