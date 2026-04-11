<?php include 'layouts/top.php'; ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>

<?php
if(isset($_POST['form_submit'])) {
    $statement = $pdo->prepare("UPDATE withdraws SET withdraw_status=?, withdraw_approval_date=? WHERE id=?");
    $statement->execute(['Approved', date('Y-m-d H:i:s'), $_POST['id']]);

    // Send email to instructor
    $statement = $pdo->prepare("SELECT * FROM withdraws WHERE id=?");
    $statement->execute([$_POST['id']]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $instructor_id = $result['instructor_id'];

    $statement = $pdo->prepare("SELECT * FROM instructors WHERE id=?");
    $statement->execute([$instructor_id]);
    $instructor = $statement->fetch(PDO::FETCH_ASSOC);
    $instructor_email = $instructor['email'];

    $email_message = "Your withdraw request has been approved."; 

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_ENCRYPTION;
    $mail->Port = SMTP_PORT;
    $mail->setFrom(SMTP_FROM);
    $mail->addAddress($instructor_email);
    $mail->isHTML(true);
    $mail->Subject = 'Withdraw is Approved!';
    $mail->Body = $email_message;
    $mail->send();

    $_SESSION['success_message'] = "Withdraw has been approved successfully.";
    header('location: withdraw.php');
    exit;
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>All Withdraws</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Instructor</th>
                                            <th>Withdraw Method</th>
                                            <th>Withdraw Amount</th>
                                            <th>Withdraw Note</th>
                                            <th>Withdraw Request Date</th>
                                            <th>Withdraw Approval Date</th>
                                            <th>Withdraw Status</th>
                                            <th class="w_100">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i=0;
                                        $statement = $pdo->prepare("SELECT t1.*,
                                                    t2.name as instructor_name 
                                                    FROM withdraws t1
                                                    JOIN instructors t2
                                                    ON t1.instructor_id = t2.id
                                                    ORDER BY t1.id DESC");
                                        $statement->execute();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $row['instructor_name']; ?></td>
                                                <td><?php echo $row['withdraw_method']; ?></td>
                                                <td>$<?php echo $row['withdraw_amount']; ?></td>
                                                <td><?php echo $row['withdraw_note']; ?></td>
                                                <td><?php echo $row['withdraw_request_date']; ?></td>
                                                <td><?php echo $row['withdraw_approval_date']; ?></td>
                                                <td>
                                                    <?php
                                                    if($row['withdraw_status'] == 'Pending') {
                                                        echo '<span class="badge bg-danger">Pending</span>';
                                                    } else if($row['withdraw_status'] == 'Approved') {
                                                        echo '<span class="badge bg-success">Approved</span>';
                                                    }
                                                    ?>
                                                <td class="pt_10 pb_10">
                                                    <form action="" method="post">
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                        <button type="submit" class="btn btn-primary btn-sm" name="form_submit" onClick="return confirm('Are you sure?');">Approve It</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>