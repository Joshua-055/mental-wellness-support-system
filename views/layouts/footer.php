<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/script.js"></script>

<?php foreach (($pageScripts ?? []) as $pageScript): ?>
    <script src="<?= BASE_URL ?>/assets/js/<?= htmlspecialchars($pageScript) ?>.js"></script>
<?php endforeach; ?>

</body>

</html>