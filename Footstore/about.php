<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us - Footcap</title>
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml" />
  <link rel="stylesheet" href="./assets/css/style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
    rel="stylesheet" />
  <style>
    .profile-dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      top: 100%;
      right: 0;
      background: var(--white);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      z-index: 1;
      min-width: 150px;
    }

    .dropdown-content a {
      display: block;
      padding: 10px 15px;
      color: var(--rich-black-fogra-29);
      text-decoration: none;
      font-size: 1.4rem;
      transition: background 0.3s ease;
    }

    .dropdown-content a:hover {
      background: var(--cultured);
    }

    /* Show dropdown on hover */
    .profile-dropdown:hover .dropdown-content {
      display: block;
    }
  </style>
</head>

<body id="top">
  <header class="header" data-header>
    <div class="container">
      <div class="overlay" data-overlay></div>

      <a href="#" class="logo">
        <img
          src="./assets/images/logo.svg"
          width="160"
          height="50"
          alt="Footcap logo" />
      </a>

      <button class="nav-open-btn" data-nav-open-btn aria-label="Open Menu">
        <ion-icon name="menu-outline"></ion-icon>
      </button>

      <nav class="navbar" data-navbar>
        <button
          class="nav-close-btn"
          data-nav-close-btn
          aria-label="Close Menu">
          <ion-icon name="close-outline"></ion-icon>
        </button>

        <a href="index.php" class="logo">
          <img
            src="./assets/images/logo.svg"
            width="190"
            height="50"
            alt="Footcap logo" />
        </a>

        <ul class="navbar-list">
          <li class="navbar-item">
            <a href="index.php" class="navbar-link">Home</a>
          </li>

          <li class="navbar-item">
            <a href="about.php" class="navbar-link">About</a>
          </li>

          <li class="navbar-item">
            <a href="products.php" class="navbar-link">Products</a>
          </li>

          <li class="navbar-item">
            <a href="shop.php" class="navbar-link">Shop</a>
          </li>

          <li class="navbar-item">
            <a href="blog.php" class="navbar-link">Blog</a>
          </li>

          <li class="navbar-item">
            <a href="contact.php" class="navbar-link">Contact</a>
          </li>
        </ul>

        <ul class="nav-action-list">
          <li>
            <button class="nav-action-btn" onclick="window.location.href='search.php'" aria-label="Search">
              <ion-icon name="search-outline" aria-hidden="true"></ion-icon>
              <span class="nav-action-text">Search</span>
            </button>
          </li>

          <li class="profile-dropdown">
            <a href="profil.php" class="nav-action-btn">
              <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
              <span class="nav-action-text">Profile</span>
            </a>
            <!-- Dropdown Content -->
            <div class="dropdown-content">
              <a href="profil.php">Profile</a>
              <a href="logout.php">Logout</a>
            </div>
          </li>
          <li>
            <button class="nav-action-btn" onclick="window.location.href='heart.php'">
              <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
              <span class="nav-action-text">Wishlist</span>
              <data class="nav-action-badge" value="<?php echo isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0; ?>" aria-hidden="true">
                <?php echo isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0; ?>
              </data>
            </button>
          </li>
          <li>
            <a href="bag.php" class="nav-action-btn" onclick="window.location.href='bag.php'">
              <ion-icon name="bag-outline" aria-hidden="true"></ion-icon>
              <span class="nav-action-text">Bag</span>
              <data class="nav-action-badge" value="<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>" aria-hidden="true">
                <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
              </data>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <article>
      <section class="section about">
        <div class="container">
          <h2 class="h2_section-title">About Us</h2>

          <div class="about-content">
            <p class="about-text">
              Welcome to <strong>Footcap</strong>, your ultimate destination
              for high-quality footwear. We are passionate about providing you
              with the best shoes that combine style, comfort, and durability.
              Our mission is to help you find the perfect pair of shoes for
              every occasion, whether it's for running, casual wear, or formal
              events.
            </p>

            <p class="about-text">
              At Footcap, we believe that the right pair of shoes can make a
              significant difference in your daily life. That's why we
              carefully curate our collection to include only the best brands
              and styles. Our team is dedicated to ensuring that you have a
              seamless shopping experience, from browsing our products to
              receiving your order at your doorstep.
            </p>

            <p class="about-text">
              We are committed to sustainability and ethical practices. Our
              products are sourced from manufacturers who share our values,
              ensuring that you can feel good about your purchase. Thank you
              for choosing Footcap – we look forward to being a part of your
              journey.
            </p>
          </div>

          <div class="about-team">
            <h3 class="h3 section-title">Meet Our Team</h3>

            <ul class="team-list">
              <li class="team-item">
                <figure class="team-member">
                  <img
                    src="https://media.licdn.com/dms/image/v2/D4D03AQGnZS1O6LDoDg/profile-displayphoto-shrink_400_400/profile-displayphoto-shrink_400_400/0/1680864401715?e=1747267200&v=beta&t=YYPRcr-LbuXNPf0gGQu9ZKE_wvm7q_5fQml3Tv8koqM"
                    alt="John Doe"
                    class="team-member-img" />
                  <figcaption class="team-member-name">John Doe</figcaption>
                  <p class="team-member-role">CEO & Founder</p>
                </figure>
              </li>

              <li class="team-item">
                <figure class="team-member">
                  <img
                    src="https://media.licdn.com/dms/image/v2/D4D03AQHzhvcjosn9Fg/profile-displayphoto-shrink_800_800/profile-displayphoto-shrink_800_800/0/1668687490902?e=1747267200&v=beta&t=1qA7MgE8Q76kdvWRjuEKODeV9aAnnvdK08MZv3juZv4"
                    alt="Jane Smith"
                    class="team-member-img" />
                  <figcaption class="team-member-name">Jane Smith</figcaption>
                  <p class="team-member-role">Head of Marketing</p>
                </figure>
              </li>

              <li class="team-item">
                <figure class="team-member">
                  <img
                    src="https://media.licdn.com/dms/image/v2/C4E03AQHOXZHV-AUiqA/profile-displayphoto-shrink_800_800/profile-displayphoto-shrink_800_800/0/1655378252624?e=1747267200&v=beta&t=jSmwujSNFjFwgMRQqPtNr55gq1CkmsZA0X2OMLf4cg0"
                    alt="Michael Johnson"
                    class="team-member-img" />
                  <figcaption class="team-member-name">Michael Johnson</figcaption>
                  <p class="team-member-role">Lead Designer</p>
                </figure>
              </li>
            </ul>
          </div>
        </div>
      </section>
    </article>
  </main>

  <footer class="footer">
    <div class="footer-top section">
      <div class="container">
        <div class="footer-brand">
          <a href="#" class="logo">
            <img
              src="./assets/images/logo.svg"
              width="160"
              height="50"
              alt="Footcap logo" />
          </a>

          <ul class="social-list">
            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-facebook"></ion-icon>
              </a>
            </li>

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-twitter"></ion-icon>
              </a>
            </li>

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-pinterest"></ion-icon>
              </a>
            </li>

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-linkedin"></ion-icon>
              </a>
            </li>
          </ul>
        </div>

        <div class="footer-link-box">
          <ul class="footer-list">
            <li>
              <p class="footer-list-title">Contact Us</p>
            </li>

            <li>
              <address class="footer-link">
                <ion-icon name="location"></ion-icon>

                <span class="footer-link-text">
                  2751 S Parker Rd, Aurora, CO 80014, United States
                </span>
              </address>
            </li>

            <li>
              <a href="tel:+557343673257" class="footer-link">
                <ion-icon name="call"></ion-icon>

                <span class="footer-link-text">+557343673257</span>
              </a>
            </li>

            <li>
              <a href="mailto:footcap@help.com" class="footer-link">
                <ion-icon name="mail"></ion-icon>

                <span class="footer-link-text">footcap@help.com</span>
              </a>
            </li>
          </ul>

          <ul class="footer-list">
            <li>
              <p class="footer-list-title">My Account</p>
            </li>

            <li>
              <a href="#" class="footer-link">
                <ion-icon name="chevron-forward-outline"></ion-icon>

                <span class="footer-link-text">My Account</span>
              </a>
            </li>

            <li>
              <a href="#" class="footer-link">
                <ion-icon name="chevron-forward-outline"></ion-icon>

                <span class="footer-link-text">View Cart</span>
              </a>
            </li>

            <li>
              <a href="#" class="footer-link">
                <ion-icon name="chevron-forward-outline"></ion-icon>

                <span class="footer-link-text">Wishlist</span>
              </a>
            </li>

            <li>
              <a href="#" class="footer-link">
                <ion-icon name="chevron-forward-outline"></ion-icon>

                <span class="footer-link-text">Compare</span>
              </a>
            </li>

            <li>
              <a href="#" class="footer-link">
                <ion-icon name="chevron-forward-outline"></ion-icon>

                <span class="footer-link-text">New Products</span>
              </a>
            </li>
          </ul>

          <div class="footer-list">
            <p class="footer-list-title">Opening Time</p>

            <table class="footer-table">
              <tbody>
                <tr class="table-row">
                  <th class="table-head" scope="row">Mon - Tue:</th>

                  <td class="table-data">8AM - 10PM</td>
                </tr>

                <tr class="table-row">
                  <th class="table-head" scope="row">Wed:</th>

                  <td class="table-data">8AM - 7PM</td>
                </tr>

                <tr class="table-row">
                  <th class="table-head" scope="row">Fri:</th>

                  <td class="table-data">7AM - 12PM</td>
                </tr>

                <tr class="table-row">
                  <th class="table-head" scope="row">Sat:</th>

                  <td class="table-data">9AM - 8PM</td>
                </tr>

                <tr class="table-row">
                  <th class="table-head" scope="row">Sun:</th>

                  <td class="table-data">Closed</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="footer-list">
            <p class="footer-list-title">Newsletter</p>

            <p class="newsletter-text">
              Authoritatively morph 24/7 potentialities with error-free
              partnerships.
            </p>

            <form action="" class="newsletter-form">
              <input
                type="email"
                name="email"
                required
                placeholder="Email Address"
                class="newsletter-input" />

              <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <p class="copyright">
          &copy; 2022 <a href="#" class="copyright-link">codewithsadee</a>.
          All Rights Reserved
        </p>
      </div>
    </div>
  </footer>

  <a href="#top" class="go-top-btn" data-go-top>
    <ion-icon name="arrow-up-outline"></ion-icon>
  </a>

  <script src="./assets/js/script.js"></script>
  <script
    type="module"
    src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script
    nomodule
    src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>