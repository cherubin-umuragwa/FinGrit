<?php 

require_once __DIR__ . '/env-loader.php';
loadEnv(__DIR__ . '/.env');

require_once 'php/auth.php'; 

?>

<!DOCTYPE html>
<html lang="en">


  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FinGrit - Analytics</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </head>

<body>
  <div class="site-wrap">
    <!-- Header -->
    <header class="fg-navbar navbar navbar-expand-lg" aria-label="fingrit navbar">
      <div class="container d-flex align-items-center justify-content-between">
        <a class="navbar-brand w-auto" href="index.php">
          <div class="logo">Fin<span>Grit</span></div>
        </a>

        <!-- Start offcanvas-->
        <div class="offcanvas offcanvas-start w-75" id="fg-navbars" tabindex="-1" aria-labelledby="fg-navbarsLabel">
          <div class="offcanvas-body align-items-lg-center">
            <ul class="navbar-nav nav me-auto ps-lg-5 mb-2 mb-lg-0">
              <li class="nav-item"><a class="nav-link scroll-link active" aria-current="page" href="#home">Home</a></li>
              <li class="nav-item"><a class="nav-link scroll-link" href="#about">About</a></li>
              <li class="nav-item"><a class="nav-link scroll-link" href="#how-it-works">How It Works</a></li>
              <li class="nav-item"><a class="nav-link scroll-link" href="#testimonials">Testimonials</a></li>
            </ul>
          </div>
        </div>
        <!-- End offcanvas-->

        <div class="ms-auto w-auto" id="btn-group1">
          <?php if ((new Auth())->isLoggedIn()): ?>
            <div data-aos="fade-up" data-aos-delay="300">
              <a href="dashboard.php" class="btn btn-primary" id="header-btn1">Dashboard</a>
            </div>
          <?php else: ?>
            <div data-aos="fade-up" data-aos-delay="300" id="navbar-btn1"><a class="btn" href="login.php">Login</a><a
                class="btn btn-white-outline" href="register.php">SignUp</a></div>
          <?php endif; ?>
          <button class="fg-navbar-toggler justify-content-center align-items-center ms-auto" data-bs-toggle="offcanvas"
            data-bs-target="#fg-navbars" aria-controls="fg-navbars" aria-label="Toggle navigation"
            aria-expanded="false">
            <svg class="fg-icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24"
              fill="none" stroke="var(--bs-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="21" x2="3" y1="6" y2="6"></line>
              <line x1="15" x2="3" y1="12" y2="12"></line>
              <line x1="17" x2="3" y1="18" y2="18"></line>
            </svg>
            <svg class="fg-icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 6 6 18"></path>
              <path d="m6 6 12 12"></path>
            </svg>
          </button>
        </div>
      </div>
  </div>
  </header>

  <!-- Main -->
  <main>

    <!-- Hero -->
    <section class="hero section" id="home">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="row">
              <div class="col-lg-11"><span class="hero-subtitle" data-aos="fade-up" data-aos-delay="0">Hey There! am
                  using <em>FinGrit</em><b> ...</b></span>
                <h1 class="hero-title mb-3" data-aos="fade-up" data-aos-delay="100"><span class="slogan" id="grit">Your
                    grit.</span> <span class="slogan" id="goals">Your goals.</span> <span class="slogan" id="money">Your
                    money.</span></h1>
                <p class="hero-description mb-4 mb-lg-5" data-aos="fade-up" data-aos-delay="200">FinGrit helps you
                  track, save, and achieve with confidence.</p>
                <div class="cta d-flex gap-2 mb-4 mb-lg-5" data-aos="fade-up" data-aos-delay="300"><a class="btn"
                    href="register.php">Get Started Now</a><a class="btn btn-white-outline" href="#features">Learn More
                    <i class="bi bi-arrow-right ms-2"></i></a></div>
                <div class="logos mb-4" data-aos="fade-up" data-aos-delay="400"><span
                    class="logos-title text-uppercase mb-4 d-block">...</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="hero-img"><img class="img-card img-fluid" src="assets/images/card-expenses.png" alt="Image card"
                data-aos="fade-down" data-aos-delay="600"><img class="img-main img-fluid rounded-4"
                src="assets/images/hero-img-1-min.jpg" alt="Hero Image" data-aos="fade-in" data-aos-delay="500"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- About -->
    <section class="about section" id="about">
      <div class="container">
        <div class="row">
          <div class="col-md-6 order-md-2">
            <div class="row justify-content-end">
              <div class="col-md-11 mb-4 mb-md-0"><span class="subtitle text-uppercase mb-3" data-aos="fade-up"
                  data-aos-delay="0">About us</span>
                <h2 class="mb-4" data-aos="fade-up" data-aos-delay="100">What are we?</h2>
                <div data-aos="fade-up" data-aos-delay="200">
                  <p>Born from the fusion of <strong><em>finance</em></strong> and <strong><em>grit</em></strong>,
                    Fingrit is a simple and smart tool that helps you take control of your money. With easy income,
                    expense, and savings tracking, Fingrit empowers you to manage your finances better, make informed
                    decisions, and grow your savings effortlessly.</p>
                  <p><strong>FinGrit</strong> envisions a world where every individual, especially youth, take charge of
                    their personal finances by making money management simple. Whether you are saving for education,
                    buildig a business, or planning for your future, we has got you covered.</p>
                </div>
                <h4 class="small fw-bold mt-4 mb-3" data-aos="fade-up" data-aos-delay="300">Key Values and Vision</h4>
                <ul class="d-flex flex-row flex-wrap list-unstyled gap-3 features" data-aos="fade-up"
                  data-aos-delay="400">
                  <li class="d-flex align-items-center gap-2"><span class="icon rounded-circle text-center"><i
                        class="bi bi-check"></i></span><span class="text">Accessibility</span></li>
                  <li class="d-flex align-items-center gap-2"><span class="icon rounded-circle text-center"><i
                        class="bi bi-check"></i></span><span class="text">Simplicity</span></li>
                  <li class="d-flex align-items-center gap-2"><span class="icon rounded-circle text-center"><i
                        class="bi bi-check"></i></span><span class="text">Education</span></li>
                  <li class="d-flex align-items-center gap-2"><span class="icon rounded-circle text-center"><i
                        class="bi bi-check"></i></span><span class="text">Innovation</span></li>
                  <li class="d-flex align-items-center gap-2"><span class="icon rounded-circle text-center"><i
                        class="bi bi-check"></i></span><span class="text">Empowerment</span></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="img-wrap position-relative"><img class="img-fluid rounded-4" src="assets/images/about_2-min.jpg"
                alt="fingrit image placeholder" data-aos="fade-up" data-aos-delay="0">
              <div class="mission-statement p-4 rounded-4 d-flex gap-4" data-aos="fade-up" data-aos-delay="100">
                <div class="mission-icon text-center rounded-circle"><i class="bi bi-lightbulb fs-4"></i></div>
                <div>
                  <h3 class="text-uppercase fw-bold">Mission Statement</h3>
                  <p class="fs-5 mb-0">Our mission is to make personal finance simple, accessible, and empowering for
                    everyone, everywhere.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features -->
    <section class="section features" id="features">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="d-lg-flex p-5 rounded-4 content" data-aos="fade-in" data-aos-delay="0">
              <div class="row">
                <div class="col-lg-5 mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="0">
                  <div class="row">
                    <div class="col-lg-11">
                      <div class="h-100 flex-column justify-content-between d-flex">
                        <div>
                          <h2 class="mb-4">What make us stand out?</h2>
                          <p class="mb-5">FinGrit is the simplest tool to help you track your personal finances, set
                            goals for your future and keep an eye on how closer your getting to them and also help you
                            to make informed decisions regarding your money.</p>
                        </div>
                        <div class="align-self-start"><a
                            class="glightbox btn btn-play d-inline-flex align-items-center gap-2"
                            href="#" data-gallery="video"><i
                              class="bi bi-play-fill"></i> Demo Video</a></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="row justify-content-end">
                    <div class="col-lg-11">
                      <div class="row">
                        <div class="col-sm-6" data-aos="fade-up" data-aos-delay="0">
                          <div class="icon text-center mb-4"><i class="bi bi-currency-dollar"></i></div>
                          <h3 class="fs-6 fw-bold mb-3">Track Expenses Easily</h3>
                          <p>Quickly log transactions and view them in organized categories.</p>
                        </div>
                        <div class="col-sm-6" data-aos="fade-up" data-aos-delay="100">
                          <div class="icon text-center mb-4"><i class="bi bi-bullseye"></i></div>
                          <h3 class="fs-6 fw-bold mb-3">Set & Achieve Goals</h3>
                          <p>Create savings goals and track your progress with visual indicators.</p>
                        </div>
                        <div class="col-sm-6" data-aos="fade-up" data-aos-delay="200">
                          <div class="icon text-center mb-4"><i class="bi bi-bar-chart"></i></i></div>
                          <h3 class="fs-6 fw-bold mb-3">Visualize Your Spending</h3>
                          <p>See your income and expenses in clear, insightful charts.</p>
                        </div>
                        <div class="col-sm-6" data-aos="fade-up" data-aos-delay="300">
                          <div class="icon text-center mb-4"><i class="bi bi-shield-lock"></i></div>
                          <h3 class="fs-6 fw-bold mb-3">Private & Secure</h3>
                          <p>Your data is encrypted and protected with secure sessions.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- How it works -->
    <section class="section howitworks" id="how-it-works">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-6 text-center mx-auto"><span class="subtitle text-uppercase mb-3" data-aos="fade-up"
              data-aos-delay="0">How it works</span>
            <h2 data-aos="fade-up" data-aos-delay="100">How FinGrit Works</h2>
            <p data-aos="fade-up" data-aos-delay="200">Our platform is designed to make managing your finances simple
              and efficient. Follow these easy steps to get started: </p>
          </div>
        </div>
        <div class="row g-md-5">
          <div class="col-md-6 col-lg-3">
            <div class="step-card text-center h-100 d-flex flex-column justify-content-start position-relative"
              data-aos="fade-up" data-aos-delay="0">
              <div data-aos="fade-right" data-aos-delay="500"><img class="arch-line" src="assets/images/arch-line.svg"
                  alt="fingrit image placeholder"></div><span
                class="step-number rounded-circle text-center fw-bold mb-5 mx-auto">1</span>
              <div>
                <h3 class="fs-5 mb-4">Sign Up</h3>
                <p>Create your free account in seconds.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="600">
            <div class="step-card reverse text-center h-100 d-flex flex-column justify-content-start position-relative">
              <div data-aos="fade-right" data-aos-delay="1100"><img class="arch-line reverse"
                  src="assets/images/arch-line-reverse.svg" alt="fingrit image placeholder"></div><span
                class="step-number rounded-circle text-center fw-bold mb-5 mx-auto">2</span>
              <h3 class="fs-5 mb-4">Add Transactions</h3>
              <p>Log your income and expenses.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="1200">
            <div class="step-card text-center h-100 d-flex flex-column justify-content-start position-relative">
              <div data-aos="fade-right" data-aos-delay="1700"><img class="arch-line" src="assets/images/arch-line.svg"
                  alt="fingrit image placeholder"></div><span
                class="step-number rounded-circle text-center fw-bold mb-5 mx-auto">3</span>
              <h3 class="fs-5 mb-4">Explore Features</h3>
              <p>Access your dashboard for a summary of your finances: income, expenses, balance, saving goals, recent
                transactions, ...</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="1800">
            <div class="step-card last text-center h-100 d-flex flex-column justify-content-start position-relative">
              <span class="step-number rounded-circle text-center fw-bold mb-5 mx-auto">4</span>
              <div>
                <h3 class="fs-5 mb-4">Watch Progress</h3>
                <p>See your savings grow and goals get closer.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats -->
    <section class="stats section">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="d-flex flex-wrap content rounded-4" data-aos="fade-up" data-aos-delay="0">
              <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-item">
                  <h3 class="fs-1 fw-bold"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="5"
                      data-purecounter-duration="2">0</span><span>K+</span></h3>
                  <p class="mb-0">Users actively managing their finances</p>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-item">
                  <h3 class="fs-1 fw-bold"> <span>$</span><span class="purecounter" data-purecounter-start="0"
                      data-purecounter-end="500" data-purecounter-duration="2">0</span><span>K+</span></h3>
                  <p class="mb-0">Total savings tracked</p>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-item">
                  <h3 class="fs-1 fw-bold"><span class="purecounter" data-purecounter-start="0"
                      data-purecounter-end="35" data-purecounter-duration="2">0</span><span>%</span></h3>
                  <p class="mb-0">Unnecessary spending reduced</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="section testimonials" id="testimonials">
      <div class="container">
        <div class="row mb-5">
          <div class="col-lg-5 mx-auto text-center"><span class="subtitle text-uppercase mb-3" data-aos="fade-up"
              data-aos-delay="0">Testimonials</span>
            <h2 class="mb-3" data-aos="fade-up" data-aos-delay="100">What Our Users Say</h2>
            <p data-aos="fade-up" data-aos-delay="200">Real people achieving real financial goals</p>
          </div>
        </div>
        <div class="row g-4" data-masonry="{&quot;percentPosition&quot;: true }">
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
            <div class="testimonial rounded-4 p-4">
              <blockquote class="mb-3">
                &ldquo;
                FinGrit helped me save for my dream vacation. The goal tracking kept me motivated!
                &rdquo;
              </blockquote>
              <div class="testimonial-author d-flex gap-3 align-items-center">
                <div class="author-img"><img class="rounded-circle img-fluid" src="assets/images/teacher.png"
                    alt="fingrit image placeholder"></div>
                <div class="lh-base"><strong class="d-block">Sarah M.</strong><span>Teacher</span></div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="testimonial rounded-4 p-4">
              <blockquote class="mb-3">
                &ldquo;
                Finally a finance app that's simple and actually helps me understand my spending.
                &rdquo;
              </blockquote>
              <div class="testimonial-author d-flex gap-3 align-items-center">
                <div class="author-img"><img class="rounded-circle img-fluid" src="assets/images/freelancer.png"
                    alt="fingrit image placeholder"></div>
                <div class="lh-base"><strong class="d-block">David J.</strong><span>Freelancer</span></div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="testimonial rounded-4 p-4">
              <blockquote class="mb-3">
                &ldquo;
                I paid off $5,000 in debt using FinGrit's tracking and goal features. Life changing!
                &rdquo;
              </blockquote>
              <div class="testimonial-author d-flex gap-3 align-items-center">
                <div class="author-img"><img class="rounded-circle img-fluid" src="assets/images/nurse.png"
                    alt="fingrit image placeholder"></div>
                <div class="lh-base"><strong class="d-block">Amanda L.</strong><span>Nurse</span></div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="testimonial rounded-4 p-4">
              <blockquote class="mb-3">
                &ldquo;
                With FinGrit, I can easily trackmy monthly salary and side income. I've managed to build an emergency
                fund for the first time in years.
                &rdquo;
              </blockquote>
              <div class="testimonial-author d-flex gap-3 align-items-center">
                <div class="author-img"><img class="rounded-circle img-fluid" src="assets/images/waiter.png"
                    alt="fingrit image placeholder"></div>
                <div class="lh-base"><strong class="d-block">Micheal T.</strong><span>Waiter</span></div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
            <div class="testimonial rounded-4 p-4">
              <blockquote class="mb-3">
                &ldquo;
                Before, I spent money without knowing where it went. Now, FinGrit shows me my daily expenses clearly,
                and I save little by little every week.
              </blockquote>
              <div class="testimonial-author d-flex gap-3 align-items-center">
                <div class="author-img"><img class="rounded-circle img-fluid" src="assets/images/taxi_driver.png"
                    alt="fingrit image placeholder"></div>
                <div class="lh-base"><strong class="d-block">David O.</strong><span>Taxi Driver</span></div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
            <div class="testimonial rounded-4 p-4">
              <blockquote class="mb-3">
                &ldquo;
                I used to think saving was impossible with my small earnings. <strong>FinGrit</strong> helped me see
                that even small amounts matter. Today I have savings for my children's needs.
                &rdquo;
              </blockquote>
              <div class="testimonial-author d-flex gap-3 align-items-center">
                <div class="author-img"><img class="rounded-circle img-fluid" src="assets/images/tailor.png"
                    alt="fingrit image placeholder"></div>
                <div class="lh-base"><strong class="d-block">Noella B.</strong><span>Tailor</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer pt-5 pb-5">
      <div class="container">
        <div class="row justify-content-between mb-5 g-xl-5">
          <div class="col-md-4 mb-5 mb-lg-0">
            <a class="navbar-brand w-auto" href="index.html">
              <div class="logo">Fin<span>Grit</span></div>
            </a>
            <p class="mb-4">Money managed with grit. We are your practical and inspiring finance companion who will
              never let your finances go out of your control.</p>
          </div>
          <div class="col-md-7">
            <div class="row g-2">
              <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <h3 class="mb-3">Quick Links</h3>
                <ul class="list-unstyled">
                  <li><a href="#home">Home</a></li>
                  <li><a href="#about">About</a></li>
                  <li><a href="#how-it-works">How it works</a></li>
                  <li><a href="#testimonials">Testimonials</a></li>
                  <li><a href="#">Fin Literacy <br>Courses <span class="badge ms-1">Coming Soon</span></a></li>
                </ul>
              </div>
              <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <h3 class="mb-3">Account</h3>
                <ul class="list-unstyled">
                  <li><a href="login.php">Login</a></li>
                  <li><a href="register.php">Register</a></li>
                  <li><a href="dashboard.php">Dashboard</a></li>
                  <li><a href="#">Language <span class="badge ms-1">Coming Soon</span></a></li>
                </ul>
              </div>
              <div class="col-md-6 col-lg-4 mb-4 mb-lg-0 quick-contact">
                <h3 class="mb-3">Contact</h3>
                <p class="d-flex mb-3"><i class="bi bi-geo-alt-fill me-3"></i><span>Remote</span>
                </p><a class="d-flex mb-3" href="mailto:fingrit@gmail.com"><i
                    class="bi bi-envelope-fill me-3"></i><span>fingrit@gmail.com</span></a></p>
                <p><a class="d-flex mb-3" href="tel://+256768128932"><i
                      class="bi bi-telephone-fill me-3"></i><span>+256768128932</span></a></p>
              </div>
            </div>
          </div>
        </div>
        <div class="row credits pt-3">
          <div class="col-xl-8 text-center text-xl-start mb-3 mb-xl-0">
            &copy;
            <span id="year">2025</span> FinGrit.
            All rights reserved.
          </div>
        </div>
      </div>
    </footer>

  </main>
  </div>

  <!-- Back to Top -->
  <button title="back-to-top" id="back-to-top"><i class="bi bi-arrow-up-short"></i></button>

  <!-- Year automatic update -->
  <script>
    document.getElementById("year").textContent = new Date().getFullYear();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/imagesloaded@5.0.0/imagesloaded.pkgd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/isotope-layout@3.0.6/dist/isotope.pkgd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/js/glightbox.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
  <script src="js/main.js"></script>
</body>

</html>