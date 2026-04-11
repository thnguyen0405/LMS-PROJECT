        <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;
        
        if(isset($_POST['form_subscribe'])) {
            try {
                if(empty($_POST['email'])) {
                    throw new Exception('Email can not be empty.');
                }
                $statement = $pdo->prepare("SELECT * FROM subscribers WHERE email=?");
                $statement->execute(array($_POST['email']));
                $total = $statement->rowCount();
                if($total) {
                    throw new Exception('Email already exists.');
                }

                $token = hash('sha256', time() . random_bytes(16));
                $link = BASE_URL.'subscriber-verify.php?email='.$_POST['email'].'&token='.$token;
                $email_message = 'Please click on this link to verify the subscription: <br>';
                $email_message .= '<a href="'.$link.'">Click Here</a>';

                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = SMTP_HOST;
                $mail->SMTPAuth = true;
                $mail->Username = SMTP_USERNAME;
                $mail->Password = SMTP_PASSWORD;
                $mail->SMTPSecure = SMTP_ENCRYPTION;
                $mail->Port = SMTP_PORT;
                $mail->setFrom(SMTP_FROM);
                $mail->addAddress($_POST['email']);
                $mail->addReplyTo(SMTP_FROM);
                $mail->isHTML(true);
                $mail->Subject = 'Verify Subscription';
                $mail->Body = $email_message;
                $mail->send();


                $statement = $pdo->prepare("INSERT INTO subscribers (email,token,status) VALUES (?,?,?)");
                $statement->execute(array($_POST['email'],$token,0));

                $_SESSION['success_message'] = 'An email is sent to your email. Please check.';
                header('location: ' . $_SERVER['HTTP_REFERER']);
                exit;

            } catch (Exception $e) {
                $error_message = $e->getMessage();
                $_SESSION['error_message'] = $error_message;
                header('location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }
        ?>
        <div class="footer pt_70">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="item pb_50">
                            <h2 class="heading">Navigate</h2>
                            <ul class="useful-links">
                                <li><a href="<?php echo BASE_URL; ?>"><i class="fas fa-angle-right"></i> Home</a></li>
                                <li><a href="<?php echo BASE_URL; ?>courses.php?title=&price=&language=&category=&review=&level="><i class="fas fa-angle-right"></i> Courses</a></li>
                                <li><a href="<?php echo BASE_URL; ?>instructors"><i class="fas fa-angle-right"></i> Instructors</a></li>
                                <li><a href="<?php echo BASE_URL; ?>terms"><i class="fas fa-angle-right"></i> Terms & Conditions</a></li>
                                <li><a href="<?php echo BASE_URL; ?>privacy"><i class="fas fa-angle-right"></i> Privacy Policy</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="item pb_50">
                            <h2 class="heading">Categories</h2>
                            <ul class="useful-links">
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC LIMIT 5");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    ?>
                                    <li><a href="<?php echo BASE_URL; ?>courses.php?title=&price=&language=&category=<?php echo $row['id']; ?>&review=&level="><i class="fas fa-angle-right"></i> <?php echo $row['name']; ?></a></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="item pb_50">
                            <h2 class="heading">Contact</h2>
                            <div class="list-item">
                                <div class="left">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="right">
                                    34 Antiger Lane, USA, 12937
                                </div>
                            </div>
                            <div class="list-item">
                                <div class="left">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="right">contact@example.com</div>
                            </div>
                            <div class="list-item">
                                <div class="left">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="right">122-222-1212</div>
                            </div>
                            <ul class="social">
                                <li><a href=""><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href=""><i class="fab fa-twitter"></i></a></li>
                                <li><a href=""><i class="fab fa-youtube"></i></a></li>
                                <li><a href=""><i class="fab fa-linkedin-in"></i></a></li>
                                <li><a href=""><i class="fab fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="item pb_50">
                            <h2 class="heading">Newsletter</h2>
                            <p>
                                To get the latest news from our website, please
                                subscribe us here:
                            </p>
                            <form action="" method="post">
                                <div class="form-group">
                                    <input type="text" name="email" class="form-control" placeholder="Email Address">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" name="form_subscribe" value="Subscribe Now">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="copyright">
                            Copyright &copy; <?php echo date('Y'); ?>, EduZora. All Rights Reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="scroll-top">
            <i class="fas fa-angle-up"></i>
        </div>

        <script src="<?php echo BASE_URL; ?>dist-front/js/custom.js"></script>

        <?php if(isset($_SESSION['success_message'])): ?>
        <script>
            iziToast.success({
                message: "<?php echo $_SESSION['success_message']; ?>",
                color: 'green',
                position: 'bottomRight',
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>


        <?php if(isset($_SESSION['error_message'])): ?>
        <script>
            iziToast.error({
                message: "<?php echo $_SESSION['error_message']; ?>",
                color: 'red',
                position: 'bottomRight',
            });
        </script>
        <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
    </body>
</html>