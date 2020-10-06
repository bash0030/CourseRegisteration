<?php        

    function ValidateUserId($userId){
            if ($userId == ""){return 1;}
    }

    function ValidateName($name){
        if ($name == ""){return 1;}
    }

    function ValidatePhoneNumber ($phoneNumberExp, $phoneNumber){
        $valid2 = (bool) preg_match($phoneNumberExp, $phoneNumber);
        if ($valid2 == false) { return 1; }
    }  

    function ValidatePassword ($passwordExp, $passWord){
        $valid3 = (bool) preg_match($passwordExp, $passWord);
        if ($valid3 == false) { return 1; }
    }         
    function ValidateEqualPassword ($passWord, $passwordAgain){
        if ($passWord != $passwordAgain) {return 1; }
    }
    function ValidateBlankPassword($passWord){
        if ($passWord == ""){return 1;}
    }
   // function clearNewUserSession() {
  //  unset($_SESSION['userIdTxt']);
  //  unset($_SESSION['nameTxt']);
  //  unset($_SESSION['phoneNumberTxt']);
  //  unset($_SESSION['passwordTxt']);
  //  unset($_SESSION['passwordAgainTxt']);
  //  unset($_SESSION['studentIdTxt']);


   //function ValidateBlankAlbum($albumTxt){
   //     if ($albumTxt == ""){
   //         return "Album is required";
   //     }
   // }
    
    
?>