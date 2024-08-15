<?php

    class AuthController extends Controller
    {

        private function validate($data)
        {
            return htmlspecialchars(strip_tags(trim($data)));
        }

        public function login()
        {
            try{
                if($_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    $username = $this->validate($_POST['username']);
                    $password=  $this->validate($_POST['password']);

                    if(empty($username) || empty($password))
                    {
                        throw new Exception("All fields are required");
                    }

                    $this->model('User');
                    if($this->model->login($username, $password))
                    {
                        session_regenerate_id(true);
                        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
                        header("Location: /profile");
                        exit();
                    }
                    else{
                        throw new Exception("Invalid username or password");
                    }
                }
            }
            catch(Exception $e){
               error_log($e->getMessage());
               $this->view->error = "An Error Occured During Login"; 
            }
            $this->view('login');
        }


        public function register(){
            try{
                if($_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    $username = $this->validate($_POST['username']);
                    $password = $this->validate($_POST['password']);
                    $confirm_password = $this->validate($_POST['confirm_password']);    

                    if(empty($username) || empty($password) || empty($confirm_password))
                    {
                        throw new Exception("All Fields Are Required");
                    }

                    if($password != $confirm_password)
                    {
                        throw new Exception("Passwords Do Not Match");
                    }

                    if(strlen($password) < 6)
                    {
                        throw new Exception("Password Must Be At Least 6 Characters Long");
                    }

                    $this->model('User');
                    if($this->model->userExists($username))
                    {
                        throw new Exception("Username Already Exists");
                    }

                    if($this->model->register($username, $password)){
                        header('Location: /login');
                        exit();
                    }
                    else{
                        throw new Exception("Registration Failed");
                    }
                }
            }
            catch(Exception $e){
                error_log($e->getMessage());
                $this->view->error = "An Error Occured During Registration";
            }

            $this->view('register');
        }

        public function profile()
        {
            try
            {
                $this->model('User');
                if(!$this->model->isLoggedIn()){
                    header('Location: /login');
                    exit();
                }
                $this->view('profile');
            }
            catch(Exception $e)
            {
                error_log($e->getMessage());
                header('Location: /login');
                exit();
            }
        }

        public function updateProfile(){
            try{
                if($_SERVER['REQUEST_METHOD'] === 'POST' && $this->model->isLoggedIn())
                {
                    $new_username = $this->validate($_POST['username']);
                    $new_password = $this->validate($_POST['password']);
                    $confirm_password = $this->validate($_POST['confirm_password']);

                    if(empty($new_username) || empty($new_password) || empty($confirm_password))
                    {
                        throw new Exception("All Fields Are Required");
                    }

                    if($new_password != $confirm_password)
                    {
                        throw new Exception("Passwords Do Not Match");
                    }

                    if(strlen($new_password) < 6)
                    {
                        throw new Exception("Password Must Be At Least 6 Characters Long");
                    }

                    $this->model('User');
                    // $_SESSION['user_id'] is the $user['id'] of the users in the Users Models. In the Login method you can see that we set the $user['id'] to a $_SESSION['user_id']
                    if($this->model->updateProfile($_SESSION['user_id'], $new_username, $new_password))
                    {
                        $_SESSION['username'] = $new_username;
                        header("Location: /profile");
                        exit();
                    }
                    else{
                        throw new Exception("Profile Update Failed");
                    }
                }
            }
            catch(Exception $e)
            {
                error_log($e->getMessage());
                $this->view->error = "An Error Occured During Profile Update";
            }

            $this->view('profile');
        }

        public function logout()
        {
            session_unset();
            session_destroy();
            header('Location: /login');
            exit();
        }
    }

?>