<ul class="list-group list-group-flush">
    
<li class="list-group-item <?php echo ($cur_page == 'instructor-dashboard.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>instructor-dashboard">Dashboard</a>
    </li>
    
    <li class="list-group-item <?php echo ($cur_page == 'instructor-course-create.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>instructor-course-create">Create Course</a>
    </li>

    <li class="list-group-item <?php echo ($cur_page == 'instructor-courses.php' || $cur_page == 'instructor-course-edit-basic.php' || $cur_page == 'instructor-course-edit-featured-photo.php' || $cur_page == 'instructor-course-edit-featured-banner.php' || $cur_page == 'instructor-course-edit-featured-video.php' || $cur_page == 'instructor-course-edit-curriculum.php' || $cur_page == 'instructor-course-edit-curriculum-lesson.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>instructor-courses">All Courses</a>
    </li>

    <li class="list-group-item <?php echo ($cur_page == 'instructor-coupon-view.php' || $cur_page == 'instructor-coupon-setup.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>instructor-coupon-view">All Coupons</a>
    </li>

    <li class="list-group-item <?php echo ($cur_page == 'instructor-message.php' || $cur_page == 'instructor-message-detail.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>instructor-message">Messages</a>
    </li>

    <li class="list-group-item <?php echo ($cur_page == 'instructor-revenue.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>instructor-revenue">Revenue</a>
    </li>

    <li class="list-group-item <?php echo ($cur_page == 'instructor-withdraw.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>instructor-withdraw">Withdraw</a>
    </li>

    <li class="list-group-item <?php echo ($cur_page == 'instructor-profile.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>instructor-profile">Edit Profile</a>
    </li>
    
    <li class="list-group-item">
        <a href="<?php echo BASE_URL; ?>instructor-logout">Logout</a>
    </li>
</ul>