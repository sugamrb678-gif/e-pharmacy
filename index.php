<!DOCTYPE html>
<html>
<head>
<title>E-Pharmacy</title>

<style>
    /* Animations */
.hero{
 animation:fadeIn .8s ease;
}

.hero h1{
 animation:slideUp .8s ease;
}

.hero p{
 animation:slideUp 1s ease;
}

.btn{
 transition:.3s;
}

.btn:hover{
 transform:translateY(-4px) scale(1.03);
}

.card{
 transition:.3s;
}

.card:hover{
 transform:translateY(-8px);
 box-shadow:0 10px 25px #0005;
}

nav a{
 transition:.3s;
}

nav a:hover{
 transform:translateY(-3px);
}

@keyframes fadeIn{
 from{opacity:0}
 to{opacity:1}
}

@keyframes slideUp{
 from{
  opacity:0;
  transform:translateY(25px);
 }
 to{
  opacity:1;
  transform:translateY(0);
 }
}
*{box-sizing:border-box}
body{
 margin:0;font-family:Arial;color:#123;
 background:linear-gradient(#002c5570,#002c5570),
 url("images/pharmacy_bg.jpg") center/cover fixed;
}

header{
 background:#063d91;padding:12px 6%;
 display:flex;justify-content:space-between;align-items:center;
 box-shadow:0 3px 12px #0005;
}
.logo{display:flex;align-items:center;gap:10px;color:white;font-size:28px;font-weight:bold}
.logo img{width:45px;height:45px;border-radius:50%}
nav a{
 color:white;text-decoration:none;padding:11px 16px;margin:4px;
 border-radius:7px;font-weight:bold
}
.login{background:white;color:#0868ce!important}
nav a:hover{background:#159cff}

.hero{text-align:center;color:white;padding:100px 20px}
.tagline{color:#42c8ff;letter-spacing:4px;font-weight:bold}
.hero h1{font-size:50px;margin:12px}
.hero h1 span{color:#20baff}
.btn,.card a{
 display:inline-block;background:#087bea;color:white;
 padding:12px 22px;margin:6px;border-radius:7px;
 text-decoration:none;font-weight:bold
}
.pharmacist{background:#263d55}

.features{
 display:flex;justify-content:center;gap:20px;
 padding:20px;flex-wrap:wrap
}
.card{
 background:#fffffff2;width:320px;padding:25px;
 border-radius:15px;box-shadow:0 5px 20px #0004;
 text-align:center
}
.card h2{color:#0868ce}
.card p{color:#555}

.about{
 margin:20px 5%;padding:35px;text-align:center;
 background:#eff8ffed;border-radius:15px
}
.about h2{color:#0868ce}
.about-box{
 display:flex;justify-content:center;gap:50px;flex-wrap:wrap
}

footer{
 background:#033475;color:white;padding:30px 7%;
}
.footer{
 display:flex;justify-content:space-between;
 flex-wrap:wrap;gap:30px
}
footer h3{color:#42c8ff}
footer a{display:block;color:white;text-decoration:none;margin:7px 0}
footer a:hover{color:#42c8ff}
.bottom{
 text-align:center;border-top:1px solid #ffffff44;
 margin-top:20px;padding-top:15px
}

@media(max-width:700px){
 header{flex-direction:column;gap:10px}
 .hero h1{font-size:38px}
}
</style>
</head>

<body>

<header>
<div class="logo">
<img src="images/logo_bg.png">
E-Pharmacy
</div>

<nav>
<a href="index.php">Home</a>
<a class="login" href="auth/login.php">👤 Customer Login</a>
<a class="login" href="auth/pharmacist_login.php">💼 Pharmacist Login</a>
</nav>
</header>

<section class="hero">
<p class="tagline">YOUR HEALTH • OUR PRIORITY</p>
<h1>Your Health, <span>Our Priority</span></h1>
<p>"Quality medicines and trusted healthcare"</p>

<a class="btn" href="auth/login.php">👤 Customer Login</a>
<a class="btn pharmacist" href="auth/pharmacist_login.php">💼 Pharmacist Login</a>
</section>

<section class="features">

<div class="card">
<h2>💊 Medicines</h2>
<p>Browse available medicines.</p>
<a href="customer/medicines.php">Browse</a>
</div>

<div class="card">
<h2>📋 Prescriptions</h2>
<p>Upload your prescription.</p>
<a href="customer/prescriptions.php">Upload</a>
</div>

<div class="card">
<h2>🛒 Easy Ordering</h2>
<p>Add medicines to your cart.</p>
<a href="customer/cart.php">View Cart</a>
</div>

</section>

<section class="about" id="about">
<h2>About Us</h2>
<p>E-Pharmacy makes purchasing medicines simple, secure and convenient.</p>

<div class="about-box">
<div>🛡️<b> 100% Genuine</b></div>
<div>🔒<b> Secure & Safe</b></div>
<div>🎧<b> 24/7 Support</b></div>
</div>
</section>

<footer>

<div class="footer">

<div>
<h3>💊 E-Pharmacy</h3>
<p>Your Health, Our Priority</p>
</div>

<div>
<h3>Quick Links</h3>
<a href="index.php">Home</a>
<a href="#about">About Us</a>
<a href="customer/medicines.php">Medicines</a>
<a href="customer/prescriptions.php">Prescriptions</a>
</div>

<div>
<h3>Contact Us</h3>
<p>📞 +977 9813329039</p>
<p>✉️ support@e-pharmacy.com</p>
<p>📍 Kathmandu, Nepal</p>
</div>

<div>
<h3>Follow Us</h3>
<p>🔵 Facebook &nbsp; 🔷 Twitter &nbsp; 🟣 Instagram</p>
</div>

</div>

<div class="bottom">
© 2026 E-Pharmacy | All Rights Reserved
</div>

</footer>

</body>
</html>