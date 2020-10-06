<?php
    session_start(); // sessions starts 
    include 'Common/Header.php';
    include 'Common/Functions.php';
    
    $userId = htmlspecialchars($_POST["userId"]);
    $_SESSION['userId'] = $userId;
    
    $passWord = htmlspecialchars($_POST["passWord"]);
    
    $_SESSION['passWord'] = $passWord;
    
    $_SESSION['name'] = $name;
    $userIdError = "";
    $passwordError = "";
    $validateError = "";    
   
    //Submit button:
    if(isset($_POST['btnsubmit']))
    {
         //VALIDATORS:
        if (ValidateUserId($userId) == 1)
        { $userIdError = "User ID cannot be blank!"; }
        else { $studentIdError = ""; }
      
        if (ValidateBlankPassword ($passWord) == 1)
        { $passwordError = "Password cannot be blank!"; } 
        else { $passwordError = ""; }  
        
        //If passing the validators:
        if ($studentIdError == "" && $passwordError == "")
        {            
            $hashed_password = sha1($passWord);            
            
            $validateError = "Ready to code!";
            //Connection to DBO            
            $dbConnection = parse_ini_file("Common/db_connection.ini");        	
            extract($dbConnection);
            $myPdo = new PDO($dsn, $user, $password);                 

            //Query database to verify StudentId and Password
            //INSERT INTO `User`(`UserId`, `Name`, `Phone`, `Password`) VALUES ([value-1],[value-2],[value-3],[value-4])
            $sqlStatement = 'SELECT * FROM User WHERE UserId = :PlaceHolderUserID AND Password = :PlaceHolderPassword';
            $pStmt = $myPdo->prepare($sqlStatement);                                           
            $pStmt ->execute(array(':PlaceHolderUserID' => $userId, ':PlaceHolderPassword' => $hashed_password));      
            $chkAccount = $pStmt->fetch(); //store first result of query to $chkAccount        

            if ($chkAccount['UserId'] != "") //user is in database and password matches
            {                
                $_SESSION['name'] = $chkAccount[1] ; //storing user's name in a session                 
                //redirects user to last active page (if any previously accessed)
                // SESSION "activePage" starts 
                if ($_SESSION['activePage'] != ""){
                    exit(header('Location: '.$_SESSION['activePage']));
                }else{
                    exit(header('Location: CurrentRegistration.php'));
                }
            }
            else //if student does not match the database
            { 
                $validateError = "Incorrect ID and/or password!";                  
            }    
        }
    }
    
    //Clear button:
    if(isset($_POST['btnclear']))
    {
      //  clearNewUserSession();
      $_SESSION['studentId'] = "";       
        $_SESSION['passWord'] = "";
    }   
?>
    <div class="container-fluid">
        <h1>Log In</h1><br>
        <h4>You need to <a href="NewUser.php">sign up</a> if you are a new user!</h4><br/>
        
        <form method='post' action=Login.php>            
            <div class='row'>
                <div class='col-lg- col-md-4 col-sm-4' style='color:red'> <?php print $validateError;?></div>
            </div>
            <br>
            <div class='form-group row'>
                <div class="col-lg-1 col-md-1 col-sm-2">
                    <label for='userId' class='col-form-label'><b>Student ID:</b> </label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4">
                    <input type='text' class='form-control' id='userId'  value='<?php print $_SESSION['userId'];?>' name='userId' >
                </div>
                <div class='col-lg-4 col-md-2 col-sm-4' style='color:red'> <?php print $userIdError;?></div>
            </div>
            <br/>

            <div class='form-group row'>
                <div class="col-lg-1 col-md-1 col-sm-2">
                    <label for='passWord' class='col-form-label'><b>Password:</b> </label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4">
                    <input type='password' class='form-control' id='passWord'  value='<?php print $_SESSION['passWord'];?>' name='passWord' ></div>
                <div class='col-lg-4 col-md-2 col-sm-4' style='color:red'> <?php print $passwordError;?></div>
            </div><br>
           <!--- Submit btn ---> 
              
         <div class='row'>
                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2">&nbsp;</div>
                <div class='col-lg-1 col-md-1 col-sm-2 col-xs-2 text-left'>
                    <button type='submit' name='btnsubmit' class='btn btn-block btn-primary'>Submit</button>
                </div>
                <div class='col-lg-1 col-md-1 col-sm-2 col-xs-2 text-left'>
                    <button type='submit' name='btnclear' class='btn btn-block btn-primary'>Clear</button>
                </div>
            </div>   
        </form>
    </div>
<?php
    include 'Common/Footer.php';
?>