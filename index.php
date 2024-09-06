<?php require_once('config.php') ?>
<?php require_once( ROOT_PATH . '/includes/head_section.php') ?>
<?php require_once( ROOT_PATH . '/includes/public_functions.php') ?>
<?php require_once( ROOT_PATH . '/includes/registration_login.php') ?>
    <title>Hreniuc Petrică</title>
</head>
<body>
    <!--  container - wraps the whole page -->
    <div class="container">
        <div class="hero-section">
            <!-- navbar -->
            <?php include( ROOT_PATH . '/includes/navbar.php') ?>
            <!-- banner -->
            <?php include( ROOT_PATH . '/includes/banner.php') ?>

            <h1 class="hero-section-title">HI, I'M HRENIUC.</h1>
            <h1 class="hero-section-title-outline">HI, I'M HRENIUC.</h1>
            <div class="hero-section-copy-container">
            <img src="static/images/meV2.png" alt="me" id="me-img">
                <div class="hero-section-copy-container-groups">
                    <div class="hero-section-copy-container-text-group">
                        <p>Master's student in Marketing, Web Developer, Digital Marketer, Digital Artist</p>
                        <p>Jack of all trades, master of all of them!</p>
                        <p>Until something new appears and I have to lear it. :D</p>
                    </div>
                    <div class="hero-section-copy-container-buttons-group">
                        <button class="contact-me-btn">Contact Me</button>
                        <div class="hero-section-copy-container-buttons-group-subgroup">
                            <button><img src="static/images/github-img.png"></button>
                            <button><img src="static/images/linkedin-img.png"></button>
                            <button><img src="static/images/instagram-img.png"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

<!-- footer -->
<?php include( ROOT_PATH . '/includes/footer.php') ?>