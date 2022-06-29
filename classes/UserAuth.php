<?php
include_once 'Dbh.php';


class UserAuth extends Dbh
{
    private static $db;

    public function __construct()
    {
        self::$db = new Dbh();
    }

    //===============================================================================
    // Method To Register User
    //===============================================================================

    public function register($fullname, $email, $password, $confirmPassword, $country, $gender)
    {
        $conn = self::$db->connect();
        if ($this->checkEmailExist($email)) {
            echo "<script> alert('User With this Email Exist');</script> ";
        } else {
            if ($this->confirmPasswordMatch($password, $confirmPassword)) {
                $sql = "INSERT INTO Students (`full_names`, `email`, `password`, `country`, `gender`) VALUES ('$fullname','$email', '$password', '$country', '$gender')";
                if ($conn->query($sql)) {
                    echo "Ok";
                } else {
                    echo "Opps" . $conn->error;
                }
            } else
                echo "<script> alert('Password Does not Match');</script> ";
        }
    }

    //============================================================================
    // Method To Login User
    //============================================================================

    public function login($email, $password)
    {
        $conn = self::$db->connect();
        $sql = "SELECT * FROM Students WHERE email='$email' AND `password`='$password' LIMIT 1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            if ($userData['email'] === $email && $userData['password'] === $password) {
                session_start();

                $_SESSION['username'] = $userData['full_names'];
                header("Location: dashboard.php?login=Success");
            } else header("Location: forms/login.php?error=WrongPasswordOrEmail");
        } else {
            header("Location: forms/login.php?error=WrongPasswordOrEmail");
            exit();
        }
    }

    //============================================================================
    // Method To Get User
    //============================================================================


    public function getUser($username)
    {
        $conn = self::$db->connect();
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }


    //============================================================================
    // Method To Get All User
    //============================================================================

    public function getAllUsers()
    {
        $conn = self::$db->connect();
        $sql = "SELECT * FROM Students";
        $result = $conn->query($sql);
        echo "<html>
        <head>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
        </head>
        <body>
        <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
        <table class='table table-bordered' border='0.5' style='width: 80%; background-color: smoke; border-style: none'; >
        <tr style='height: 40px'>
            <thead class='thead-dark'> <th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th>
        </thead></tr>";
        if ($result->num_rows > 0) {
            while ($data = mysqli_fetch_assoc($result)) {

                //show data
                echo "<tr style='height: 20px'>" .
                    "<td style='width: 50px; background: gray'>" . $data['id'] . "</td>" .
                    "<td style='width: 150px'>" . $data['full_names'] . "</td>" .
                    "<td style='width: 150px'>" . $data['email'] . "</td>" .
                    " <td style='width: 150px'>" . $data['gender'] . "</td>" .
                    "<td style='width: 150px'>" . $data['country'] . "</td>" .
                    "<td style='width: 150px'> 
                    <form action='action.php' method='post'>
                    <input type='hidden' name='id'" .
                    "value=" . $data['id'] . ">" .
                    "<button class='btn btn-danger' type='submit' name='delete'> DELETE </button> </form> </td>" .
                    "</tr>";
            }
            echo "</table></table></center></body></html>";
        }
    }


    //============================================================================
    // Method To Delete User
    //============================================================================

    public function deleteUser($id)
    {
        $conn = UserAuth::$db->connect();

        $sql = "DELETE FROM Students WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) {
            header("refresh:0.5; url=action.php?all=");
        } else {
            header("refresh:0.5; url=action.php?all&message=Error");
        }
    }


    //============================================================================
    // Method To Update User
    //============================================================================

    public function updateUser($email, $password)
    {
        $conn = self::$db->connect();
        $sql = "UPDATE students SET password = '$password' WHERE email = '$email' LIMIT 1";
        if ($conn->query($sql) === TRUE) {
            header("Location: forms/login.php?update=UpdateSuccessful");
            exit();
        } else {
            header("Location: forms/resetpassword.php?error=WrongEmail");
            exit();
        }
    }


    //============================================================================
    // Method To Get User By Name
    //============================================================================

    public function checkEmailExist($email)
    {
        $conn = self::$db->connect();
        $sql = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }


    //============================================================================
    // Method To Logout User
    //============================================================================

    public function logout($email)
    {
        if (isset($_SESSION['username']) == ['username']) {
            session_unset();
            session_destroy();
            header("Location: forms/login.php");
        }
    }


    //============================================================================
    // Method To ConfirmPassword
    //============================================================================

    public function confirmPasswordMatch($password, $confirmPassword)
    {
        if ($password === $confirmPassword) {
            return true;
        } else {
            return false;
        }
    }

    //============================================================================
    // Method To Check if a User Exist(I added this method)
    //============================================================================
    public function UserExist($email, $password)
    {
        $conn = self::$db->connect();
        $sql = "SELECT email,password FROM students WHERE email = '$email' OR password = '$password' ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    //============================================================================
    // Method To Check if password Exist(I added this method)
    //============================================================================

    public function PasswordExist($password)
    {
        $conn = self::$db->connect();
        $sql = "SELECT password FROM students WHERE password = '$password' ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}
