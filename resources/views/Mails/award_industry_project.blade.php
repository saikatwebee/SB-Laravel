<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Award Industry Project</title>
</head>
<style>
body{ font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
}
</style>
<!-- Bootstrap Core CSS -->
<!--<link href="https://solututionbuggy/assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">-->
<body>
<p>Dear <?= $email_data['pfullname'] ?>,</p>

<p>Congrats for being selected as Solution Provider for the problem/project posted as per below details. Login to SolutionBuggy to view more details</p>

<h3>Project Details</h3>
<p>Project Title: <b>Project 1</b></p>
<p>Solution Seeker Name: <b><?= $email_data['ifullname'] ?></b></p>
<p>Solution Seeker Email: <b><?= $email_data['iemail'] ?></b></p>
<p>Solution Seeker Contact Number: <b><?= $email_data['iphone'] ?></b></p>


<h4><a href="http://localhost:3000/auth/login-page">Login Now</a></h4><br>

            
<p>Best Wishes<br> 
SolutionBuggy Team</p>
<img src="https://my.solutionbuggy.com/static/media/logoSb.5352e272.png" alt="SolutionBuggy">

<div style="color:#000000"> <h4 style="color:#AA3414"> Follow Us: <a href="https://www.facebook.com/solutionbuggy/ " >Facebook</a> - <a href="https://www.linkedin.com/company/solution-buggy ">Linkedin</a> - <a href="https://twitter.com/SolutionBuggy">Twitter</a> - <a href="https://plus.google.com/u/0/101907656488923015213">GooglePlus</a> - <a href="https://www.youtube.com/channel/UCoKoETJRzky2OjTFfsPro8Q ">Youtube</a> - <a href="https://in.pinterest.com/solutionbuggy/">Pinterest</a></h4><br></div>

</body>
</html>