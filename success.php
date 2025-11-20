<!DOCTYPE html>
<html>
<head>
<title>Success</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
Swal.fire({
    title: 'Order Successful!',
    text: 'Thank you. Your booking is confirmed.',
    icon: 'success',
    confirmButtonText: 'Go Home'
}).then(() => {
    window.location.href = 'dashboard.php';
});
</script>

</body>
</html>
