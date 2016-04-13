<?php
include('class.password.php');

class User extends Password{
    private $_db;
    protected $uid;

    function __construct($db){
        parent::__construct();

        $this->_db = $db;
    }

    public function is_logged_in(){
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
            return true;
        }
    }

    private function get_user_hash($username){
        try {
            $stmt = $this->_db->prepare('SELECT password, memberID FROM blog_members WHERE username = :username');
            $stmt->execute(array('username' => $username));

            $row = $stmt->fetch();
            $_SESSION['uid'] = $row['memberID'];

            return $row['password'];
        } catch(PDOException $e) {
            echo '<p class="error">'.$e->getMessage().'</p>';
        }
    }

    public function login($username,$password){
        $hashed = $this->get_user_hash($username);

        if($this->password_verify($password,$hashed) == 1){

            $_SESSION['loggedin'] = true;
            return true;
        }
    }

    public function register(array $details) {
        try {
            $details['password'] = $this->password_hash($details['password'], PASSWORD_BCRYPT);

            $stmt = $this->_db->prepare('INSERT INTO users(username, password, email, name) VALUES (:user, :pass, :email, :name)');
            $stmt->execute(array(
                ':user' => $details['user'],
                ':pass' => $details['password'],
                ':email' => $details['email'],
                ':name' => $details['name']
            ));

            header('Location: index.php');
        } catch(\PDOException $e) {
            echo('<p class="error">Sorry there was an issue with the registering, try again later</p>');
        }
    }

    public function logout(){
        session_destroy();
    }

    public function get_user()
    {
        if(isset($_SESSION['uid']) && $_SESSION['uid'] == true) {
            $stmt = $this->_db->prepare('SELECT memberID, name, email FROM blog_members WHERE memberID = :uid');
            $stmt->execute(array('uid' => $_SESSION['uid']));

            $row = $stmt->fetch();

            return $row;
        }
    }
}
?>