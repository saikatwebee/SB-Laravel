<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Payment Success</title>
</head>
<style>
body{ font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
}
</style>
<!-- Bootstrap Core CSS -->
<!--<link href="https://solututionbuggy/assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">-->
<body>
<p>Dear <?= $email_data['firstname'] ?>,</p>

<p>Your payment was successful. Please find the details below </p>


<h3>Payment Details</h3>
<p>Payment Method: <b>PayU Money</b></p>
<p>Amount: <b>Rs. <?=  $email_data['amount'] ?></b></p>
<p>Transaction ID: <b><?=  $email_data['txnid'] ?></b></p>

<h4><a href="http://localhost:3000/auth/login-page">Login Now</a></h4><br>

            
<p>Best Wishes<br> 
SolutionBuggy Team</p>
<img src="https://my.solutionbuggy.com/static/media/logoSb.5352e272.png" alt="SolutionBuggy">

<div style="color:#000000"> <h4 style="color:#AA3414"> Follow Us: <a href="https://www.facebook.com/solutionbuggy/ " >Facebook</a> - <a href="https://www.linkedin.com/company/solution-buggy ">Linkedin</a> - <a href="https://twitter.com/SolutionBuggy">Twitter</a> - <a href="https://plus.google.com/u/0/101907656488923015213">GooglePlus</a> - <a href="https://www.youtube.com/channel/UCoKoETJRzky2OjTFfsPro8Q ">Youtube</a> - <a href="https://in.pinterest.com/solutionbuggy/">Pinterest</a></h4><br></div>

</body>
</html>