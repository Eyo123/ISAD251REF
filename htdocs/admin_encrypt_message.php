<?php

$page_title = "Generate a Secure Message";

include_once('includes/admin_header.html');
require_once ('secure_input.php');

# if registration form submitted need to check it
if($_SERVER['REQUEST_METHOD']=='POST')
{ 
  $message = secure_input($_POST['message']);
  $action = secure_input($_POST['action']);
  $errors = array();

  if(empty($message))
  {
    $errors[] = 'Enter a message.';
  }
  
  # encrypt or decrypt the message
  if(empty($errors))
  {
	if($action == "encrypt")
	{
		$key = array(
		"A" => "D", "B" => "E","C" => "F", "D" => "G","E" => "H","F" => "I",
		"G" => "J", "H" => "K", "I" => "L", "J" => "M","K" => "N",
		"L" => "O","M" => "P", "N" => "Q", "O" => "R", 
		"P" => "S","Q" => "T", "R" => "U","S" => "V","T" => "W", 
		"U" => "X", "V" => "Y", "W" => "Z", "X" => "A",
		"Y" => "B", "Z" => "C"
		);

		$length=strlen($message);
		$result='';
		for ($i = 0; $i < $length; $i++) 
		{
			if (in_array(strtoupper($message[$i]), array_flip($key)))
			{
				$result .= $key[strtoupper($message[$i])];
			}
		}
	}
	else if($action == "decrypt")
	{
		$key = array(
		"A" => "X", "B" => "Y","C" => "Z", "D" => "A","E" => "B","F" => "C",
		"G" => "D", "H" => "E", "I" => "F", "J" => "G","K" => "H",
		"L" => "I","M" => "J", "N" => "K", "O" => "L", 
		"P" => "M","Q" => "N", "R" => "O","S" => "P","T" => "Q", 
		"U" => "R", "V" => "S", "W" => "T", "X" => "U",
		"Y" => "V", "Z" => "W"
		);

		$length=strlen($message);
		$result='';
		for ($i = 0; $i < $length; $i++) 
		{
			if (in_array(strtoupper($message[$i]), array_flip($key)))
			{
				$result .= $key[strtoupper($message[$i])];
			}
		}
	}
  }
  
  # if registration was successful
  if (empty($errors)) 
  {
    echo '<h3 class="text-info">Result</h3>';
	echo $result;
    include_once('includes/admin_footer.html');
    exit();
  }
  else 
  {
    echo '<p class="text-info">The following error(s) occurred:<br>' ;
    foreach ($errors as $msg)
    {
        echo " - $msg<br>";
    }
    echo 'Please try again.</p>';
  }  
}

?>

<section role="contentinfo" aria-label="Tempest Flights Generate a Secure Message Page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Generate a Secure Message</h2>
    </div>
    <div class="row">
        <form action="admin_encrypt_message.php" method="post" id="encrypt_message">
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea rows="10" name="message" class="form-control" id="message" required></textarea>
            </div>
			<div class="radio">
				<label><input type="radio" name="action" value="encrypt" checked>Encrypt</label>
            </div>
			<div class="radio">
				<label><input type="radio" name="action" value="decrypt">Decrypt</label>
            </div>
            <div class="form-group">
                <p><input type="submit" value="Calculate">
                    <input type="reset" value="Clear"></p>
            </div>
        </form>
    </div>
</div>
</section>

<?php

include_once('includes/footer.html');

?>
