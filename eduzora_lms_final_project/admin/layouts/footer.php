</div>
</div>

<script src="<?php echo BASE_URL; ?>dist-admin/js/scripts.js"></script>
<script src="<?php echo BASE_URL; ?>dist-admin/js/custom.js"></script>

<?php if(isset($_SESSION['success_message'])): ?>
<script>
    iziToast.success({
        message: "<?php echo $_SESSION['success_message']; ?>",
        color: 'green',
        position: 'topRight',
    });
</script>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>


<?php if(isset($_SESSION['error_message'])): ?>
<script>
    iziToast.error({
        message: "<?php echo $_SESSION['error_message']; ?>",
        color: 'red',
        position: 'topRight',
    });
</script>
<?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

</body>
</html>