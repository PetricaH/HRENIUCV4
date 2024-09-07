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
                            <a href="https://www.linkedin.com/in/hreniuc/" target="_blank">LinkedIn</a>
                            <a href="https://github.com/PetricaH" target="_blank">GitHub</a>
                            <a href="https://www.instagram.com/petrica_jb/" target="_blank">Instagram</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section id="my-work-section">
            <h2 class="my-work-section-title">mY worK</h2>
            <!-- RECENT ARTWORKS SECTION START -->
            <div class="different-section">
                        <h2 class="content-title">Recent Artworks</h2>
                        <hr>
                        <div class="different-section-content">
                                <?php foreach ($artworks as $art): ?>
                                        <div class="post" style="margin-left: 0px;">
                                                <img src="<?php echo BASE_URL . '/uploads/art/' . $art['art_image']; ?>" class="post_image" alt="">

                                                <?php if (isset($art['category']['name'])): ?>
                                                <a href="<?php echo BASE_URL . 'filtered_arts.php?category=' . $art['category']['id']; ?>" class="btn category">
                                                        <?php echo $art['category']['name']; ?>
                                                </a>
                                                <?php endif ?>

                                                <h3><?php echo $art['name']; ?></h3>
                                                <span><?php echo date("F j, Y", strtotime($art["created_at"])); ?></span>
                                                <a href="single_art.php?art-id=<?php echo $art['id']; ?>">
                                                <div class="post_info">
                                                        <div class="info">
                                                        <span class="read_more">View more...</span>
                                                        </div>
                                                </div>
                                                </a>
                                        </div>
                                <?php endforeach; ?>
                        </div>
                </div>


             <!-- RECENT ARTWORKS SECTION END -->


             <!-- RECENT ARTICLES SECTION START -->
                <div class="different-section">
                        <h2 class="content-title">Recent Articles</h2>
                        <hr>
                        <!-- more content still to come here ... -->
                        <div class="different-section-content">
                                <?php foreach ($posts as $post): ?>
                                        <div class="post" style="margin-left: 0px;">
                                                <img src="<?php echo BASE_URL . '/static/images/' . $post['image']; ?>" class="post_image" alt="">

                                                <?php if (isset($post['topic']['name'])): ?>
                                                        <a href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $post['topic']['id'] ?>"
                                                                class="btn category">
                                                                <?php echo $post['topic']['name'] ?>
                                                        </a>
                                                <?php endif ?>

                                                <a href="single_post.php?post-slug=<?php echo $post['slug']; ?>">
                                                        <div class="post_info">
                                                                <h3><?php echo $post['title'] ?></h3>
                                                                <div class="info">
                                                                        <span><?php echo date("F j, Y ", strtotime($post["created_at"])); ?></span>
                                                                        <span class="read_more">Read more...</span>
                                                                </div>
                                                        </div>
                                                </a>
                                        </div>
                                <?php endforeach ?>
                        </div>
                        <!-- RECENT ARTICLES SECTION END -->
        </section>
    </div>
 

<!-- footer -->
<?php include( ROOT_PATH . '/includes/footer.php') ?>