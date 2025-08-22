<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta tag -->
    <meta charset="utf-8">
    <title>Welcome To The Aaryans</title>
	<!-- favicon -->
	<link rel="icon" type="image/x-icon" href="images/favicon.png">
    <meta name="description" content="">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/animate.css">
    <link rel="stylesheet" type="text/css" href="css/line-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<!-- Banner Section Start -->
<section id="slider">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-6 pr-140 md-mb-140 md-pr-15">
                <div class="content-wrap">
                    <h1 class="it-title" style="color: black;">Institution Lead Submission DEMO Page</h1>
                    <div class="description">
                        <p class="desc">
                            
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 pl-70 md-pl-15" style="opacity: 0.8;">
                <div class="rs-contact">
                    <div class="contact-wrap">
                        <div class="content-part mb-4">
                            <h2 class="title mb-15">Submit your Query</h2>
                            <p class="desc">We will contact you as soon as possible.</p>
                        </div>
                        <form id="appointment-form" method="post">
                            <fieldset>
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <input class="from-control" type="text" id="appointment_name"
                                            name="full_name" placeholder="Name" required>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <input class="from-control" type="text" id="appointment_email"
                                            name="email" placeholder="E-Mail" required>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <input class="from-control" type="text" id="appointment_phone"
                                            name="phone_number" placeholder="Phone Number" required>
                                    </div>
                                    <div class="col-lg-12 mb-4">
                                        <input class="from-control" type="text" id="appointment_website"
                                            name="applying_for_class" placeholder="Applying for Class">
                                    </div>
									<input type="hidden" name="date_submitted" value="<?php echo date('Y-m-d H:i:s'); ?>">
									<div class="col-lg-12 mb-5">
                                        <input class="from-control" type="text" id="appointment_website"
                                            name="query" placeholder="Write your Query here.">
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <input class="readmore" type="submit" value="Submit Now">
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Banner Section End -->

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.nav.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/wow.min.js"></script>
<script src="js/custom.js"></script>
<script type="text/javascript">
	const form = document.getElementById("appointment-form");
	form.addEventListener("submit", (event) => {
    	event.preventDefault();

    	fetch("process_form.php", {
        method: "POST",
        body: new FormData(form)
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        Swal.fire({
  			title: "Thankyou!",
  			text: "Your Query has been submitted.",
  			icon: "success"
		});
		form.reset();
    })
    .catch(error => {
        console.error("Error:", error);
    });
});

</script>
</body>
</html>