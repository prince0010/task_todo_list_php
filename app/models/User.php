<?php
// Models the One who do the work into the database or communicating to the database and returning the results based on the logics here and to the controller.
    class User extends Model {

        public function userExist($username)
        {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        }
        

        public function register($username, $password)
        {
            if($this->userExist($username))
            {
                return false;
            }

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("INSERT INTO users(username, password) VALUES (?, ?)");
            return $stmt->execute([$username,$password]);
        }

        public function login($username, $password)
        {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($password, $user['password']))
            {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                return true;
            }

            return false;
        }

        public function isLoggedIn()
        {
            return isset($_SESSION['user_id']);
        }

        public function updateProfile($user_id, $new_username, $new_password)
        {
            $passwordHash = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
            $stmt->execute([$new_username, $new_password, $user_id]);
        }
    }

?>