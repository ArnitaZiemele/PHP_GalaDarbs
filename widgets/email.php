<?php
    $to = 'arnitaziemele@gmail.com';
    $subject = $_POST['subject'];
    $message = $_POST['message']; 
    $from = $_POST['from'];

	$email_subject = "New Form submission: $subject";
	$email_body = "You have received a new message from the user $from.\n".
                "Here is the message:\n $message".
    $headers = "From: $from \r\n";
    
    if(mail($to, $email_subject, $email_body, $headers)){
        echo 'Your mail has been sent successfully.';
        echo 'Ziņojums: $message';
    } 
    else{
        echo 'Unable to send email. Please try again.';
    }
?>