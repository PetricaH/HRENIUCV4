<?php require_once('config.php') ?>
<?php require_once( ROOT_PATH . '/includes/head_section.php') ?>
<?php require_once( ROOT_PATH . '/includes/public_functions.php') ?>
<?php require_once( ROOT_PATH . '/includes/registration_login.php') ?>
<?php $posts = getPublishedPosts(); ?>
<?php $artworks = getPublishedArtworks(); ?>
<?php $webdevprojects = getPublishedWebdevProjects(); ?>
<?php
// Display only the latest 4 posts
$artworks = array_slice($artworks, 0, 4);
$posts = array_slice($posts, 0, 4); 
?>
<?php if ($success): ?>
    <p class="success">Your message has been sent successfully!</p> 
<?php endif; ?>
    <title>Hreniuc Petrică</title>
</head>
<body>
    <!--  container - wraps the whole page -->
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

            <!-- RECENT WEB DEVELOPMENT PROJECTS SECTION START -->
            <div class="different-section web-dev-section">

                <div class="text-part-web-dev-section">
                    <div class="text-part-web-dev-section-inside">
                        <h2 class="content-title web-dev-title">Web Development</h2>
                        <p>With over 2 years of practical experience, I can provide exceptional services of developing websites that bring, keep, and make customers.</p>
                        <button class="ask-for-rates-btn">Ask for Rates</button>
                    </div>
                </div>
            
                <div class="post-part-web-dev-section">
                    <div class="different-section-content">
                        <?php foreach ($webdevprojects as $webdevproject): ?>
                            <div class="post" style="margin-left: 0px;">
                                <img src="<?php echo BASE_URL . '/uploads/projects/' . $webdevproject['project_image']; ?>" class="post_image" alt="">

                                <?php if (isset($webdevproject['category']['name'])): ?>
                                <a href="<?php echo BASE_URL . 'filtered_projects.php?category=' . $webdevproject['category']['id']; ?>" class="btn category">
                                    <?php echo $webdevproject['category']['name']; ?>
                                </a>
                                <?php endif ?>
                                    <div class="post_info">
                                        <h3><?php echo $webdevproject['title']; ?></h3>
                                        <span class="post-date"><?php echo date("F j, Y", strtotime($webdevproject["created_at"])); ?></span>
                                        <a href="single_project.php?project-id=<?php echo $webdevproject['id']; ?>">
                                            <span class="read_more">
                                                <span class="material-symbols-outlined expand_content_btn">expand_content</span>
                                            </span>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- RECENT WEB DEVELOPMENT PROJECTS SECTION END -->

            <!-- RECENT ARTWORKS SECTION START -->
            <div class="different-section art-section">

                        <div class="text-part-art-section">
                            <div class="text-part-art-section-inside">
                                <h2 class="content-title art-title">Recent Artworks</h2>
                                <p>Started as a hobby and passion, still is up to this date. Over 5+ years of constantly creating digital art has trained my eyes for beauty, being able to design gorgeous products.</p>
                                <button class="ask-for-rates-btn">Ask for Rates</button>
                            </div>
                        </div>

                        <div class="post-part-art-section">
                            <div class="different-section-content">
                                <?php foreach ($artworks as $art): ?>
                                    <div class="post-card">
                                        <img src="<?php echo BASE_URL . '/uploads/art/' . $art['art_image']; ?>" class="post_image" alt="">

                                        <div class="post_info">
                                                    <h3><?php echo $art['title']; ?></h3>
                                                    <span class="post-date"><?php echo date("F j, Y", strtotime($art["created_at"])); ?></span> 
                                                    <?php if (isset($art['category']['name'])): ?>
                                                        <a href="<?php echo BASE_URL . 'filtered_arts.php?category=' . $art['category']['id']; ?>" class="btn category">
                                                            <?php echo $art['category']['name']; ?>
                                                        </a>
                                                    <?php endif; ?>

                                                    <a href="single_art.php?art-id=<?php echo $art['id']; ?>">
                                                    <span class="read_more">
                                                        <span class="material-symbols-outlined expand_content_btn">expand_content</span>
                                                    </span>
                                                    </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                </div>


             <!-- RECENT ARTWORKS SECTION END -->


             <!-- RECENT DIGITAL MARKETING PROJECTS SECTION START -->
                <div class="different-section marketing-section">
                    <div class="text-part-marketing-section">
                        <div class="text-part-marketing-section-inside">
                            <h2 class="content-title marketing-title">Digital Marketing</h2>
                            <p>From Email Marketing, SEO, Paid Ads, to Marketing Campaigns, I do it all.</p>
                            <button class="ask-for-rates-btn">Ask for Rates</button>
                        </div>
                    </div>
                    <div class="post-part-marketing-section">>
                            <div class="different-section-content">
                                <?php foreach ($posts as $post): ?>
                                    <div class="post-card">
                                                <img src="<?php echo BASE_URL . 'uploads/posts/' . $post['image']; ?>" class="post_image" alt="">

                                        <div class="post_info">
                                            <h3><?php echo $post['title'] ?></h3>
                                            <span class="post-date"><?php echo date("F j, Y ", strtotime($post["created_at"])); ?></span>
                                            <?php if (isset($post['topic']['name'])): ?>
                                                <a href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $post['topic']['id'] ?>" class="btn category">
                                                    <?php echo $post['topic']['name'] ?>
                                                </a>
                                            <?php endif ?>

                                            <a href="single_post.php?post-slug=<?php echo $post['slug']; ?>">
                                            <span class="read_more">
                                                <span class="material-symbols-outlined expand_content_btn">expand_content</span>
                                            </span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                        </div>
                    </div> 
                </div>
                        <!-- RECENT DIGITAL MARKETING PROJECTS SECTION END -->
    </section>

    <section id="contact-section">
        <h2 class="contact-section-title">contacT mE</h2>
        <div class="contact-section-inside">
            <form id="contactForm" action="contact.php" method="$_POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required> 
                <br>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <br>
                <label for="message">Message</label>
                <textarea name="message" id="message" required></textarea>
                <br>

                <!-- CAPTCHA -->
                <label for="captcha">Enter the text from the image:</label>
                <img src="captcha.php" class="captcha-img" alt="CAPTCHA Image">
                <br>
                <input type="text" id="captcha" name="captcha" required>
                <br>

                <button type="submit">Submit</button>
            </form>
        </div>
    </section>

<!-- footer -->
<?php include( ROOT_PATH . '/includes/footer.php') ?>