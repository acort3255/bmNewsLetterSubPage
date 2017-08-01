<?
{
?>
<?php
    // To start buffered output for header redirecting
    ob_start();
    //
    
    class APIDataManager {
        // The database connection
        protected static $connection;

        /**
        * Connect to the database
        * 
        * @return bool false on failure / mysqli MySQLi object instance on success
        */
        public function connect() {    
        // Try and connect to the database
            if(!isset(self::$connection)) {
                // Load configuration as an array. Use the actual location of your configuration file
                $config = parse_ini_file('./config.ini');
                self::$connection = new mysqli('127.0.0.1',$config['username'],$config['password'],$config['dbname']);
            }

            // If connection was not successful, handle the error
            if(self::$connection === false) {
                // Handle error - notify administrator, log to a file, show an error screen, etc.
                return false;
            }
            return self::$connection;
        }
        
        /**
        * Insert into the database
        *
        * @param $firstName The first name string
        * @param $lastName The last name string
        * @param $email The email string
        * @return mixed The result of the mysqli::query() function
        */
        public function insertClient($firstName, $lastName, $email) {
            // Connect to the database
            $connection = $this->connect();
            $stmt = "";
            $results = "Failed to prepare statement!";
            
            if ($stmt = $connection->prepare("INSERT INTO `Basement_Systems`.`Client` (`Email`, `firstName`, `lastName`, `date`) VALUES (?, ?, ?, NOW());")) {

                /* bind parameters for markers */
                $stmt->bind_param("sss", $email, $firstName, $lastName);

                /* execute query */
                $stmt->execute();
                
                if ($connection->affected_rows != -1)
                {
                    return "success";
                }
                else
                {
                    $results = "Couldn't insert into database!";
                }
                
                /* close statement */
                $stmt->close();
            }
            
            return "error: ".$results;
        }

        /**
        * Query the database
        *
        * @param $query The query string
        * @return mixed The result of the mysqli::query() function
        */
        public function query($query) {
            // Connect to the database
            $connection = $this -> connect();

            // Query the database
            $result = $connection -> query($query);

            return $result;
        }

        /**
        * Fetch rows from the database (SELECT query)
        *
        * @param $query The query string
        * @return bool False on failure / array Database rows on success
        */
        public function select($query) {
            $rows = array();
            $result = $this -> query($query);
            if($result === false) {
            return false;
            }
            while ($row = $result -> fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }

        /**
        * Fetch the last error from the database
        * 
        * @return string Database error message
        */
        public function error() {
        $connection = $this -> connect();
        return $connection -> error;
        }

        /**
        * Quote and escape value for use in a database query
        *
        * @param string $value The value to be quoted and escaped
        * @return string The quoted and escaped string
        */
        public function quote($value) {
            $connection = $this -> connect();
            return "'" . $connection -> real_escape_string($value) . "'";
        }
        
        public function close()
        {
            $this->connection->close();
        }
    }
    
    class Form {
    
    protected $validate = true;
    protected $data = array();
    protected $action = "";
    protected $type = "";
    protected $errorMsg = "";
    function __construct($array, $type, $action, $validate){
        $this->validate = $validate;
        $this->type = $type;
        $this->action = $action;
        $this->data = $array;
        switch($type){
            case 'post':
                $this->processPost($array);
                break;
            case 'get':
                $this->processGet($array);
                break;
        }
    }
        
    function getErrorMsg()
    {
        return $this->errorMsg;
    }
    
    protected function validateData($array)
    {
        if(!$array['firstName'])
        {
            $this->errorMsg = "Please enter a First Name";
            return false;
        }
        
        if(!$array['lastName'])
        {
            $this->errorMsg = "Please enter a Last Name";
            return false;
        }
        
        if(!preg_match("/^\S+@\S+$/", $array['email'])) {
            $this->errorMsg = "Please enter a valid Email address";
            return false;
        }
        
        return true;
    }
        
    
    protected function saveData(){
        
        $dataManager = new APIDataManager();
        $dataManager->connect();
        
        $this->errorMsg = $dataManager->error();
        $results = $dataManager->insertClient($this->data['firstName'],$this->data['lastName'], $this->data['email']);
        if ($results != "success")
        {
            $this->errorMsg = $results;
        }
        //if($stmt = )
        //$this->Redirect($this->action, true);
    }

    protected function processPost($array){
        
        if($this->validate && !$this->validateData($array))
        {
            return;
        }
        
        $this->saveData();
    }
    
    protected function processGet($array){

        if($this->validate && !$this->validateData($array))
        {
            return;
        }
        
        $this->saveData();
    }
        
    protected function Redirect($url, $permanent = false)
    {
        header('Location: '.$url, true, $permanent ? 301 : 302);

        exit();
    }
}?>
<!doctype html>
<html lan="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <title>Treehouse Newsletter Signup Page</title>
    <meta name="viewport" content="wide=device-width">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto" type="text/css">
    <link rel="icon" href="https://dc69b531ebf7a086ce97-290115cc0d6de62a29c33db202ae565c.ssl.cf1.rackcdn.com/7/favicon.ico" type="image/ico">
    <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body>
    <section class="welcome">
        <h1>Welcome</h1>
        <p>Welcome to Connecticut Basement Systems New Letter Signup Page!</p>
        <p>We are running PHP verison <?= phpversion()?></p>
    </section>
    <form id="myForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <section class="form">
        <h2>What's Next?</h2>
        <p>Fill out our convenient form, to receive our monthly newsletter. Which includes updates on all our products and services.</p>
        <ul class="input-list style-1">
            <li>
                <?php
                    
                    if($_POST && isset($_POST))
                    {
                        $form = new Form($_POST, "post", "test.php", true);
                    }
    
                    if(isset($form) && $form->getErrorMsg()) {
                        echo "<p style=\"color: red;\">*",htmlspecialchars($form->getErrorMsg()),"</p>\n\n";
                    }
                ?>
            </li>
            <li><label><b>First Name</b></label>
                <input class="focus" type="text" placeholder="Enter First Name" name="firstName" required>
            </li>
            <li>
                <label><b>Last Name</b></label>
                <input class="focus" type="text" placeholder="Enter Last Name" name="lastName" required>
            </li>
            <li>
                <label><b>Email</b></label>
                <input class="focus" type="text" placeholder="Enter Email" name="email" required>
            </li>
            <li></li>
            <li id="submitBtn">
                <div class="button raised green">
                <div class="center" fit>Subcribe</div>
                <paper-ripple fit></paper-ripple>
                </div>
            </li>
            <li><label><b>Submited Form:</b></label></li>
            <li>
                <label><b>First Name:</b> <?php if(isset($form) && $form->getErrorMsg() == "") { echo $_POST['firstName']; } ?></label>
            </li>
            <li>
                <label><b>Last Name:</b> <?php if(isset($form) && $form->getErrorMsg() == "") { echo $_POST['lastName']; }?></label>
            </li>
            <li>
                <label><b>Email:</b> <?php if(isset($form) && $form->getErrorMsg() == "") { echo $_POST['email']; }?></label>
            </li>
        </ul>
    </section>
    </form>
</body>
<script>
    function didClickOnSubmitBtn(evt) {
        document.getElementById("myForm").submit();
    }
    var submitBtn = document.getElementById("submitBtn");
    submitBtn.addEventListener("click", didClickOnSubmitBtn, false);
</script>
</html>
<?
}
?>