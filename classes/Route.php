<?php
require 'classes/UserAuth.php';

class FormController extends UserAuth
{
    public $id;
    public $fullname;
    public $email;
    public $password;
    public $confirmpassword;
    public $country;
    public $gender;

    public function __construct()
    {   parent::__construct();
    
    }

    public function handleForm()
    {
      
        switch (true) {
            case isset($_POST['register']):
                //unpack all data for registering
                $this->fullname = $_POST['fullnames'];
                $this->email = $_POST['email'];
                $this->password = $_POST['password'];
                $this->confirmPassword = $_POST['confirmPassword'];
                $this->gender = $_POST['gender'];
                $this->country = $_POST['country'];
                //check if user exist
                if ($this->UserExist($this->email, $this->password) == true) {
                    echo "<script> alert('User Exist' ); </script>";
                    header("Location: forms/register.php?error=userExist&name=$this->fullname");
                    exit();
                }
                $this->register($this->fullname, $this->email, $this->password, $this->confirmPassword, $this->country, $this->gender);
                break;
//==============================================================                
            case isset($_POST['login']):
                //unpack all data for login
                $this->email = $_POST['email'];
                $this->password = $_POST['password'];
                $this->login($this->email, $this->password);
                break;
//=================================================================
            case isset($_POST['logout']):
                //unpack all data for logout
                $this->email = $_POST['logout'];
                $this->logout($this->email);
                break;
//==================================================================
            case isset($_POST['delete']):
                //unpack all data for deleting
                $this->id = $_POST['id'];
                $this->deleteUser($this->id);
                break;
//======================================================================
            case isset($_POST['reset']):
                //unpack all data for updating password
                $this->email = $_POST['email'];
                $this->password = $_POST['password'];
                if ($this->PasswordExist($this->password) == true) {
                    header("Location: forms/resetpassword.php?error=PasswordTaken");
                    exit();
                }else
                $this->updateUser($this->email, $this->password);
                break;
//=====================================================================
            case (isset($_POST['all']) || isset($_GET['all'])):
               // case isset($_GET['all']):
                //unpack all data for getting all users
                $this->getAllUsers();
                break;
            default:
                echo 'No form was submitted';
                break;
        }
    }
}
