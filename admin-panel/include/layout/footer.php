<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
    crossorigin="anonymous"></script>

<script>
    function togglePassword(id, password) {
        const passwordSpan = document.getElementById('password-' + id);
        const eyeIcon = document.getElementById('eye-icon-' + id);

        if (passwordSpan.dataset.visible === "true") {
            passwordSpan.textContent = '**********';
            passwordSpan.dataset.visible = "false";
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            passwordSpan.textContent = password;
            passwordSpan.dataset.visible = "true";
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    }
</script>

</body>

</html>