<?php include 'layouts/top.php'; ?>

<?php
if(isset($_POST['form_submit'])) {
    try {
        if($_POST['question'] == '') {
            throw new Exception("Question can not be empty");
        }
        if($_POST['answer'] == '') {
            throw new Exception("Answer can not be empty");
        }


        $statement = $pdo->prepare("INSERT INTO faqs (question,answer) VALUES (?,?)");
        $statement->execute([$_POST['question'],$_POST['answer']]);

        $success_message = "Data is inserted successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: ".ADMIN_URL."faq-view.php");
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".ADMIN_URL."faq-add.php");
        exit;
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Add FAQ</h1>
            <div>
                <a href="<?php echo ADMIN_URL; ?>faq-view.php" class="btn btn-primary"><i class="fas fa-plus"></i> View All</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Question *</label>
                                            <input type="text" class="form-control" name="question">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Answer *</label>
                                            <textarea name="answer" class="form-control h_200"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="form_submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>