<?
{
?>
<?php
    $form;
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

}
?>
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
        <p>We are running PHP verison <?= phpversion() ?></p>
    </section>
    <form id="myForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <section class="form">
        <h2>What's Next?</h2>
        <p>Fill out convenient form, to receive our monthly newsletter. Which includes updates on all products and services.</p>
        <ul class="input-list style-1">
            <li>
                <?php
                    
                    if($_POST && isset($_POST))
                    {
                        $form = new Form($_POST, "post", "", true);
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
        alert("Did click on submit button!");
        document.getElementById("myForm").submit();
    }
    var submitBtn = document.getElementById("submitBtn");
    submitBtn.addEventListener("click", didClickOnSubmitBtn, false);
</script>
</html>
<?
}
?>