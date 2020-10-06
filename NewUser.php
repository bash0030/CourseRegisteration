<?php
session_start();
include 'Common/Functions.php';
include 'Common/Header.php';

try {
    $myPdo = new PDO(
        dsn="mysql:host=localdb;dbname=CST8257;Data Source=127.0.0.1:50514;charset=utf8"
Id=azure;
password=6#vWHD_$

    );
    echo "connection succesful";
} catch (PDOException $e) {
    echo "DB connection Failed: " . $e->getMessage();
}



// userid session
$userId = htmlspecialchars($_POST["userId"]);
$_SESSION['userId'] = $userId;
// name session
$name = htmlspecialchars($_POST["name"]);
$_SESSION['name'] = $name;
// phone number session
$phoneNumber = htmlspecialchars($_POST["phoneNumber"]);
$_SESSION['phoneNumber'] = $phoneNumber;

// password session
$passWord = htmlspecialchars($_POST["passWord"]);
$_SESSION['passWord'] = $passWord;

// password Again seesion
$passwordAgain = htmlspecialchars($_POST["passwordAgain"]);
$_SESSION['passwordAgain'] = $passwordAgain;
// Encryption for phone number 
$phoneNumberExp = "/^[2-9]\d{2}-[2-9]\d{2}-\d{4}$/";
// Encrytion for password
$passwordExp = '/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{6,}/';

// list of Errors 
$studentIdError = "";
$nameError = "";
$phoneNumberError = "";
$passwordError = "";
$validateError = "";


// when clicked on submit button 
if (isset($_POST['btnsubmit'])) {
    //VALIDATORS:
    if (ValidateUserId($userId) == 1) {
        $userIdError = "User ID cannot be blank!";
    } else {
        $userIdError = "";
    }

    if (ValidateName($name) == 1) {
        $nameError = "Name can't be blank!";
    } else {
        $nameError = "";
    }
    if (ValidatePhoneNumber($phoneNumberExp, $phoneNumber) == 1) {
        $phoneNumberError = "Incorrect Phone Number!";
    } else {
        $phoneNumberError = "";
    }

    if (ValidatePassword($passwordExp, $passWord) == 1) {
        $passwordError = "Minimum 6 characters, 1 upper, lower case and 1 digit!";
    } else {
        $passwordError = "";
    }

    if (ValidateEqualPassword($passWord, $passwordAgain)) {
        $passwordAgainError = "Passwords does not match!";
    } else {
        $passwordAgainError = "";
    }


    //IF PASSING ALL THE VALIDATIONS:
    if ($userIdError == "" && $nameError == "" && $phoneNumberError == "" && $passwordError == "" && $passwordAgainError == "") {
        //encrypting the password
       // $hashed_password = sha1($passWord);

        //Connection to DBO            
        $dbConnection = parse_ini_file("Common/db_connection.ini");
        extract($dbConnection);
        $myPdo = new PDO($dsn, $user, $password);

        //Query database to see if userId already exists      
        $sqlStatement = 'SELECT * FROM User WHERE User.UserId = :PlaceHolderUserID';
        $pStmt = $myPdo->prepare($sqlStatement);
        $pStmt->execute(array(':PlaceHolderUserID' => $userId));
        $chkAccount = $pStmt->fetch(); //store first result of query to $chkAccount  


        if ($chkAccount['UserId'] == "") //user does not exist
        {
            $sql = "INSERT INTO User VALUES( :id, :name, :phoneNumber, :passWord)";
            $pStmt = $myPdo->prepare($sql);
            $pStmt->execute(array(':id' => $userId, ':name' => $name, ':phoneNumber' => $phoneNumber, ':passWord' => $hashed_password));
            $pStmt->commit;
            //Clear the Session to avoid overlap between other pages
            // clearNewUserSession();
            //Redirect to login page 
            header('Location: Login.php');
            //  header('Location: MyFriends.php'); //ARE WE SUPPOSED TO GO TO MY FRIENDS PAGE?
            exit;
        } else //if student already exists
        {
            $validateError = "Another user with this ID has already existed!";
        }
    }
}
//Clear button:
if (isset($_POST['btnclear'])) {
    //   clearNewUserSession();
    $_SESSION['studentId'] = "";
    $_SESSION['name'] = "";
    $_SESSION['phoneNumber'] = "";
    $_SESSION['passWord'] = "";
    $_SESSION['passwordAgain'] = "";
    $_SESSION['userId'] = "";
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <h1 class="text-center">Sign Up</h1>
        </div>
    </div>
    <h4>All fields are required:</h4>
    <form method='post' action=NewUser.php>
        <!-- <div class="col-lg-4">
                 <small class = "text-danger"><?php echo $validateError ?></small>
            </div> -->
        <div class='col-lg-2 col-md-2 col-sm-2' style='color:red'> <?php print $validateError; ?></div><br>


        <div class='form-group row '>
            <div class='col-lg-1 col-md-2 col-sm-2'>
                <label for='userId' class='col-form-label'><b>Student ID:</b> </label>
            </div>
            <div class='col-lg-4 col-md-4 col-sm-4'>
                <input type='text' class='form-control' id='userId' value='<?php print $_SESSION['userId']; ?>' name='userId'>
            </div>
            <div class='col-lg-4 col-md-4 col-sm-4' style='color:red'> <?php print $userIdError; ?></div>
        </div>
        <br />

        <div class='form-group row'>
            <div class='col-lg-1 col-md-2 col-sm-2'>
                <label for='name' class='col-form-label'><b>Name:</b></label>
            </div>
            <div class='col-lg-4 col-md-4 col-sm-4'>
                <input type='text' class='form-control' id='name' value='<?php print $_SESSION['name']; ?>' name='name'></div>
            <div class='col-lg-4 col-md-4 col-sm-4' style='color:red'> <?php print $nameError; ?></div>
        </div>
        <br />

        <div class='form-group row'>
            <div class='col-lg-1 col-md-2 col-sm-2'>
                <label for='phoneNumber' class='col-form-label'><b>Phone Number:</b><br />
                    <small id="phoneHelp" class="text-muted">
                        (nnn-nnn-nnnn)
                    </small>
                </label>
            </div>
            <div class='col-lg-4 col-md-4 col-sm-4'>
                <input type='text' class='form-control' id='phoneNumber' value='<?php print $_SESSION['phoneNumber']; ?>' name='phoneNumber'>
            </div>
            <div class='col-lg-4 col-md-4 col-sm-4' style='color:red'><?php print $phoneNumberError; ?></div>
        </div>

        <div class='form-group row'>
            <div class='col-lg-1 col-md-2 col-sm-2'>
                <label for='passWord' class='col-form-label'><b>Password:</b> </label>
            </div>
            <div class='col-lg-4 col-md-4 col-sm-4'>
                <input type='password' class='form-control' id='passWord' value='<?php print $_SESSION['passWord']; ?>' name='passWord'>
            </div>
            <div class='col-lg-4 col-md-4 col-sm-4' style='color:red'> <?php print $passwordError; ?></div>
        </div><br>

        <div class='form-group row'>
            <div class='col-lg-1 col-md-2 col-sm-2'>
                <label for='passwordAgain' class='col-form-label'><b>Password Again:</b></label>
            </div>
            <div class='col-lg-4 col-md-4 col-sm-4'>
                <input type='password' class='form-control' id='passwordAgain' value='<?php print $_SESSION['passwordAgain']; ?>' name='passwordAgain'>
            </div>
            <div class='col-lg-4 col-md-4 col-sm-4' style='color:red'> <?php print $passwordAgainError; ?></div>
        </div><br>
        <!----Submit and Clear btn -->
        <div class='row'>
            <div class="col-lg-1 col-md-2 "></div>
            <div class='col-lg-2 col-md-2 col-sm-2 '>
                <button type='submit' name='btnsubmit' class='btn btn-block btn-primary'>Submit</button>
            </div>
            <div class='col-lg-2 col-md-2 col-sm-2 '>
                <button type='submit' name='btnclear' class='btn btn-block btn-primary'>Clear</button>

            </div>
        </div>
      </div>
     <br />
    <br />
    </form>

<?php
include 'Common/Footer.php';
?>
