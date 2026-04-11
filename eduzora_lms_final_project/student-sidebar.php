<ul class="list-group list-group-flush">
    <li class="list-group-item <?php echo ($cur_page == 'student-dashboard.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>student-dashboard">Dashboard</a>
    </li>
    <li class="list-group-item <?php echo ($cur_page == 'student-courses.php' || $cur_page == 'student-course.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>student-courses">Enrolled Courses</a>
    </li>
    <li class="list-group-item <?php echo ($cur_page == 'student-order.php' || $cur_page == 'student-order-invoice.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>student-order">Orders</a>
    </li>
    <li class="list-group-item <?php echo ($cur_page == 'student-wishlist.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>student-wishlist">Wishlist</a>
    </li>
    <li class="list-group-item <?php echo ($cur_page == 'student-message.php' || $cur_page == 'student-message-detail.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>student-message">Message</a>
    </li>
    <li class="list-group-item <?php echo ($cur_page == 'student-review.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>student-review">Reviews</a>
    </li>
    <li class="list-group-item <?php echo ($cur_page == 'student-profile.php') ? 'active' : '' ?>">
        <a href="<?php echo BASE_URL; ?>student-profile">Edit Profile</a>
    </li>
    <li class="list-group-item">
        <a href="<?php echo BASE_URL; ?>student-logout">Logout</a>
    </li>
</ul>