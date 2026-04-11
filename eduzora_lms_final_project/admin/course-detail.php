<?php include 'layouts/top.php'; ?>

<?php
$statement = $pdo->prepare("SELECT 
                    t1.*,
                    t2.name as category_name,
                    t3.name as level_name,
                    t4.name as language_name,
                    t5.name as instructor_name,
                    t5.email as instructor_email
                    FROM courses t1
                    JOIN categories t2
                    ON t1.category_id = t2.id
                    JOIN levels t3
                    ON t1.level_id = t3.id
                    JOIN languages t4
                    ON t1.language_id = t4.id
                    JOIN instructors t5
                    ON t1.instructor_id = t5.id
                    WHERE t1.id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
$course = $statement->fetchAll();
if(!$total) {
    header("location: ".ADMIN_URL."course-view.php");
    exit;
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between">
            <h1>Course Detail</h1>
            <div>
                <a href="<?php echo ADMIN_URL; ?>course-detail-curriculum.php?id=<?php echo $_REQUEST['id']; ?>" class="btn btn-success"><i class="fas fa-list"></i> View Curriculum</a>
                <a href="<?php echo ADMIN_URL; ?>course-view.php" class="btn btn-primary"><i class="fas fa-eye"></i> View All Courses</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th class="w_200">Title</th>
                                        <td><?php echo $course[0]['title']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Slug</th>
                                        <td><?php echo $course[0]['slug']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Price</th>
                                        <td><?php echo $course[0]['price']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Price (Old)</th>
                                        <td><?php echo $course[0]['price_old']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Category</th>
                                        <td><?php echo $course[0]['category_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Level</th>
                                        <td><?php echo $course[0]['level_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Language</th>
                                        <td><?php echo $course[0]['language_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Instructor Name</th>
                                        <td><?php echo $course[0]['instructor_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Instructor Email</th>
                                        <td><?php echo $course[0]['instructor_email']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Student</th>
                                        <td><?php echo $course[0]['total_student']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Rating</th>
                                        <td><?php echo $course[0]['average_rating']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Video</th>
                                        <td><?php echo $course[0]['total_video']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Resource</th>
                                        <td><?php echo $course[0]['total_resource']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Video Length</th>
                                        <td>
                                            <?php echo convert_seconds_to_minutes_hours($course[0]['total_video_second']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>
                                            <?php echo date('F d, Y', strtotime($course[0]['updated_at'])); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Featured Photo</th>
                                        <td>
                                            <img src="<?php echo BASE_URL; ?>uploads/<?php echo $course[0]['featured_photo']; ?>" alt="" class="w_200">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Featured Banner</th>
                                        <td>
                                            <img src="<?php echo BASE_URL; ?>uploads/<?php echo $course[0]['featured_banner']; ?>" alt="" class="w_200">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Featured Video Type</th>
                                        <td>
                                            <?php echo $course[0]['featured_video_type']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Featured Video Content</th>
                                        <td>
                                            <?php if($course[0]['featured_video_type'] == 'youtube'): ?>
                                                <div class="iframe_1">
                                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $course[0]['featured_video_content']; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                </div>
                                            <?php elseif($course[0]['featured_video_type'] == 'vimeo'): ?>
                                                <div class="iframe_1">
                                                    <iframe src="https://player.vimeo.com/video/<?php echo $course[0]['featured_video_content']; ?>" width="560" height="315" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                                </div>
                                            <?php else: ?>
                                                <div class="mp4_1">
                                                    <video width="560" height="315" controls>
                                                        <source src="<?php echo BASE_URL; ?>uploads/<?php echo $course[0]['featured_video_content']; ?>" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
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