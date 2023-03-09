<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    #img {
        width: 100%;
        height: 65vh;
    }
    body{
        width:100%;
        height:785px;
        background-image:url('https://my.solutionbuggy.com/static/media/login4.db1d1a72.jpg');
        background-size:cover;
        background-position:center;
        background-repeat:no-repeat;

    }
    </style>
</head>

<body>
    <div class="container">
        <!-- Content here -->


        <div class="card mt-5">

            <div class="card-body">
                <div class="row">
                   
                    <div class="col-lg-8">
                        <div class="text-center mt-5">
                            <h4 class="text-success"><i class="fa fa-check-circle" aria-hidden="true"></i> Payment Success </h4>
                            <h5>Your payment has been successfully processed</h5>
                            <h5>Here are the details of your tanscation for your reference</h5>
                        </div>
                       
                        <table class="table table-bordered table-striped table-hover">
                            <tr>
                                <th>Status</th>
                                <td>Failed</td>
                            </tr>
                            <tr>
                                <th>Plan Selected</th>
                                <td>Basic</td>
                            </tr>
                            <tr>
                                <th>Ammount</th>
                                <td>14000</td>
                            </tr>
                            <tr>
                                <th>Transaction Id</th>
                                <td>dfrer2221eerg </td>
                            </tr>
                        </table>
                        <div class="text-center mt-2">
                            <button class="btn btn-danger back"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Back</button>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-3">
                        <image src="https://my.solutionbuggy.com/static/media/payment_suc.0414da68.png" id="img"
                            alt="Failure image" />
                       
                           
                    </div>
                </div>

            </div>
        </div>



    </div>
    <script>
        $(document).on("click",".back",()=>{
            window.location.href='http://localhost:3000/customer/buy-memberships';
        })

    </script>
</body>

</html>