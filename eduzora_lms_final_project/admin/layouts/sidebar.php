<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?php echo ADMIN_URL; ?>dashboard.php">Admin Panel</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?php echo ADMIN_URL; ?>dashboard.php"></a>
        </div>

        <ul class="sidebar-menu">

            
            <li class="<?php if($cur_page == 'dashboard.php') {echo 'active';} ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>dashboard.php"><i class="fas fa-file"></i> <span>Dashboard</span></a></li>

            <li class="nav-item dropdown <?php echo ($cur_page == 'setting-commission.php' || $cur_page == 'setting-withdraw.php' || $cur_page == 'setting-logo.php' || $cur_page == 'setting-favicon.php') ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file"></i><span>Settings</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo ($cur_page == 'setting-commission.php') ? 'active' : '' ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>setting-commission.php"><i class="fas fa-angle-right"></i> Sales Commission</a></li>
                    <li class="<?php echo ($cur_page == 'setting-withdraw.php') ? 'active' : '' ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>setting-withdraw.php"><i class="fas fa-angle-right"></i> Withdraw</a></li>
                    <li class="<?php echo ($cur_page == 'setting-logo.php') ? 'active' : '' ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>setting-logo.php"><i class="fas fa-angle-right"></i> Logo</a></li>
                    <li class="<?php echo ($cur_page == 'setting-favicon.php') ? 'active' : '' ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>setting-favicon.php"><i class="fas fa-angle-right"></i> Favicon</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php echo ($cur_page == 'instructor-active.php' || $cur_page == 'instructor-pending.php' || $cur_page == 'instructor-add.php' || $cur_page == 'instructor-edit.php') ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file"></i><span>Instructor Section</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo ($cur_page == 'instructor-active.php') ? 'active' : '' ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>instructor-active.php"><i class="fas fa-angle-right"></i> Active Instructors</a></li>
                    <li class="<?php echo ($cur_page == 'instructor-pending.php') ? 'active' : '' ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>instructor-pending.php"><i class="fas fa-angle-right"></i> Pending Instructors</a></li>
                </ul>
            </li>


            <li class="nav-item dropdown <?php echo ($cur_page == 'student-active.php' || $cur_page == 'student-pending.php' || $cur_page == 'student-add.php' || $cur_page == 'student-edit.php') ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file"></i><span>Student Section</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo ($cur_page == 'student-active.php') ? 'active' : '' ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>student-active.php"><i class="fas fa-angle-right"></i> Active Students</a></li>
                    <li class="<?php echo ($cur_page == 'student-pending.php') ? 'active' : '' ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>student-pending.php"><i class="fas fa-angle-right"></i> Pending Students</a></li>
                </ul>
            </li>


            <li class="nav-item dropdown <?php echo ($cur_page == 'category-view.php' || $cur_page == 'category-add.php' || $cur_page == 'category-edit.php' || $cur_page == 'level-view.php' || $cur_page == 'level-add.php' || $cur_page == 'level-edit.php' || $cur_page == 'language-view.php' || $cur_page == 'language-add.php' || $cur_page == 'language-edit.php') ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file"></i><span>Course Elements</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo ($cur_page == 'category-view.php' || $cur_page == 'category-add.php' || $cur_page == 'category-edit.php') ? 'active' : '' ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>category-view.php"><i class="fas fa-angle-right"></i> Category</a></li>
                    <li class="<?php echo ($cur_page == 'level-view.php' || $cur_page == 'level-add.php' || $cur_page == 'level-edit.php') ? 'active' : '' ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>level-view.php"><i class="fas fa-angle-right"></i> Level</a></li>
                    <li class="<?php echo ($cur_page == 'language-view.php' || $cur_page == 'language-add.php' || $cur_page == 'language-edit.php') ? 'active' : '' ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>language-view.php"><i class="fas fa-angle-right"></i> Language</a></li>
                </ul>
            </li>


            <li class="nav-item dropdown <?php echo ($cur_page == 'course-view.php' || $cur_page == 'course-detail.php' || $cur_page == 'course-detail-curriculum.php') ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file"></i><span>Course Section</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo ($cur_page == 'course-view.php') ? 'active' : '' ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>course-view.php"><i class="fas fa-angle-right"></i> All Courses</a></li>
                </ul>
            </li>

            <li class="<?php if($cur_page == 'order.php' || $cur_page == 'order-invoice.php') {echo 'active';} ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>order.php"><i class="fas fa-file"></i> <span>Orders</span></a></li>

            <li class="<?php if($cur_page == 'withdraw.php') {echo 'active';} ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>withdraw.php"><i class="fas fa-file"></i> <span>Withdraws</span></a></li>

            <li class="<?php if($cur_page == 'testimonial-view.php'||$cur_page == 'testimonial-add.php'||$cur_page == 'testimonial-edit.php') {echo 'active';} ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>testimonial-view.php"><i class="fas fa-file"></i> <span>Testimonials</span></a></li>

            <li class="<?php if($cur_page == 'subscriber-view.php'||$cur_page == 'subscriber-export.php') {echo 'active';} ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>subscriber-view.php"><i class="fas fa-file"></i> <span>Subscribers</span></a></li>

            <li class="<?php if($cur_page == 'post-view.php'||$cur_page == 'post-add.php'||$cur_page == 'post-edit.php') {echo 'active';} ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>post-view.php"><i class="fas fa-file"></i> <span>Blog</span></a></li>

            <li class="<?php if($cur_page == 'faq-view.php'||$cur_page == 'faq-add.php'||$cur_page == 'faq-edit.php') {echo 'active';} ?>"><a class="nav-link" href="<?php echo ADMIN_URL; ?>faq-view.php"><i class="fas fa-file"></i> <span>FAQ</span></a></li>

            

        </ul>
    </aside>
</div>