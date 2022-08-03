<?php include ( "./inc/connect.inc.php" ); ?>
<!DOCTYPE html>
<html>
<head>
	<title>ToSpecial</title>
	<link rel="stylesheet" type="text/css" href="./css/r_home.css"/>
	 <link rel="stylesheet" href="bootstrap.min.css"/> 
   

</head>
<body>
	<section id="NavigationBar">
		<nav class="navbar navbar-expand-lg navbar-light">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" href="r_home.php">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="tospecial_about.php">About</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="r_login.php">Login</a>
					</li>
				</ul>

		</nav>
		<div class="navbar-logo">
			<a href="r_home.php">
				<img src=".\r_images\logo.png">
			</a>
		</div>
	</section>
	<section class="headerimage" style="background-image: url(r_images/new.jpg); ">
		
    </section>	
    <section class="home-path">
    	<div class="home-paths__item">
          <a href="famtospe.php" style="background-image: url(r_images/family.jpeg);"><em>For Family</em></a>
          <p class="summary">Guiding families and caregivers to information, and support</p>
          <a href="famtospe.php" target="" class="more">Learn More</a>
        </div>
        <div class="home-paths__item">
          <a href="doctospe.php" style="background-image: url(r_images/doctor.jpg);"><em>For Doctor</em></a>
          <p class="summary">One may respond to a therapy that is not effective for others</p>
          <a href="doctospe.php" target="" class="more">Learn More</a>
        </div>
        <div class="home-paths__item">
          <a href="edutospe.php" style="background-image: url(r_images/educator.jpg);"><em>For Educator</em></a>
          <p class="summary">Greatly affect autistic children’s ability to learn and succeed</p>
          <a href="edutospe.php" target="" class="more">Learn More</a>
        </div>
        <div class="home-paths__item">
          <a href="evetospe.php" style="background-image: url(r_images/everyone.jpg);"><em>For Everyone</em></a>
          <p class="summary">Buliding acceptance and understanding for atuistic children</p>
          <a href="evetospe.php" target="" class="more">Learn More</a>
        </div>
    </section>
    <section class="home-updates">
    	<h2>Newsworthy</h2>
    	<div class="home-updates__wrap">
    		<div class="content-news">
    			<h3><span class="icon"><img src="r_images/megephone.svg" alt="Megaphone"></span>News &amp; Headlines</h3>
    			<div class="content-news__item">
    				<p class="meta">March 12, 2019</p>
    				<h2>Stakeholders’ Voices Heard on Severe Challenging Behavior</h2>
    				<p class="summary">The Assembly Human Services Committee heard parents and advocates testify on the challenges faced by individuals with developmental disabilities and mental health conditions, including the high prevalence of severe challenging behavior. </p>
    				<a href="#" class="more">Learn more</a>
    			</div>
    		<div class="content-news__item">
                <p class="meta">March 06, 2019</p>
                <h2>Governor’s Proposed State FY 2020 Budget</h2>
                <p class="summary">Governor Murphy delivered his budget address for the State Fiscal Year 2020, including the allocation of new funding.</p>
                <a href="#" class="more">Learn more</a>
              </div>
               <a href="#" target class="button">View all news</a>
    		</div>
    		<div class="upcoming-event">
    			<h3><span class="icon"><img src="r_images/icon-event.svg" alt="Megaphone"></span>Upcoming Events</h3>
    			<div class="upcoming-event__item">
    				<div class="upcoming-event__photo">
    				<img src="r_images/parents.jpeg" alt="Strategic Approaches to the IEP">
                </div>
                <div class="upcoming-event__text">
                  <p class="meta">March 25, 2019</p>
                  <h2>Strategic Approaches to the IEP</h2>
                  <p class="summary">FREE WORKSHOP:  Learn to navigate the special education system and help students obtain meaningful benefit, </p>
                  <a href="#" class="more">Learn more</a>
                </div>
    			</div>
    			<div class="upcoming-event__item">
                <div class="upcoming-event__photo">
                  <img src="r_images/help.jpg" alt="Charmed by Charity">
                </div>
                <div class="upcoming-event__text">
                  <p class="meta">April 6, 2019</p>
                  <h2>Charmed by Charity</h2>
                  <p class="summary">Fifteen percent of all ALEX AND ANI sales on April 2 will be donated to Autism New Jersey.</p>
                  <a href="#" class="more">Learn more</a>
                </div>
              </div>
              <a href="#" target class="button">View all Events</a>
    		</div>

    	</div>
    </section>
    <section class="home-sign-up">
    	<div class="home-sign-up__wrap">
    		<div class="home-sign-up__text">
    			<h2>Stay Connected!</h2> 
    			<p>To build acceptance for the special ones.</p>   			
    		</div>   		
    		<div class="home-sign-up__form">
			<a href="signin.php">
            <button type="submit" class="btn">SIGN UP</button>
          </a>
    	</div>
    </section>
    <section class="footer">
    	<div class="footer-end"></div>
    </section>
</body>
</html>