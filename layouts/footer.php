
<!-- layouts/footer.php -->
<div class="py-4 text-center">
  <div class="container">
    <p class="mb-0 fs-6 text-muted">
      © <?= date('Y') ?> Sistem Peminjaman Dosir. Design by Firdinal Juliandre.
    </p>
  </div>
</div>


</div> <!-- body-wrapper -->
</div> <!-- page-wrapper -->

<!-- JS Assets -->
<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
<script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sidebarmenu.js"></script>
<script src="../assets/js/app.min.js"></script>
<script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
<script src="../assets/libs/simplebar/dist/simplebar.js"></script>
<script src="../assets/js/dashboard.js"></script>

</body>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const viewButtons = document.querySelectorAll('.view-pdf');
    const pdfFrame = document.getElementById('pdfFrame');

    viewButtons.forEach(button => {
      button.addEventListener('click', function () {
        const pdfUrl = this.getAttribute('data-pdf');
        pdfFrame.src = pdfUrl;
        const pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'));
        pdfModal.show();
      });
    });
  });
</script>





</html>
