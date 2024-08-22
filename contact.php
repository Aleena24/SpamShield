<?php

// Retrieve form data. Use POST as the primary method and fallback to GET if needed.
$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : htmlspecialchars($_GET['name']);
$email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($_GET['email']);
$comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : htmlspecialchars($_GET['comment']);

// Flag to indicate which method it uses. If POST, set it to 1
$post = ($_POST) ? 1 : 0;

// Initialize an errors array
$errors = [];

// Simple server-side validation for POST data, and validate the email
if (!$name) $errors[] = 'Please enter your name.';
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
if (!$comment) $errors[] = 'Please enter your message.';

// If the errors array is empty, send the mail
if (empty($errors)) {

    // Recipient
    $to = 'spamshield824@gmail.com'; 

    // Sender - from the form
    $from = $name . ' <' . $email . '>';

    // Subject and the HTML message
    $subject = 'Message from ' . $name;
    $message = 'Name: ' . $name . '<br/><br/>
               Email: ' . $email . '<br/><br/>        
               Message: ' . nl2br($comment) . '<br/>';

    // Send the mail
    $result = sendmail($to, $subject, $message, $from);

    // If POST was used, display the message straight away
    if ($post) {
        if ($result) {
            echo 'Thank you! We have received your message.';
        } else {
            echo 'Sorry, there was an unexpected error. Please try again later.';
        }

    // Else if GET was used, return the boolean value so that
    // Ajax script can react accordingly: 1 means success, 0 means failed
    } else {
        echo $result;
    }

// If the errors array has values, display the errors
} else {
    foreach ($errors as $error) {
        echo $error . '<br/>';
    }
    echo '<a href="index.html">Back</a>';
    exit;
}

// Simple mail function with HTML header
function sendmail($to, $subject, $message, $from) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . $from . "\r\n";
    
    $result = mail($to, $subject, $message, $headers);
    
    return $result ? 1 : 0;
}

?>
