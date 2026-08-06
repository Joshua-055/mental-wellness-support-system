<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<?php $sharedScriptVersion = filemtime(APP_ROOT . '/assets/js/script.js') ?: 1; ?>
<script src="<?= BASE_URL ?>/assets/js/script.js?v=<?= (int) $sharedScriptVersion ?>"></script>

<?php foreach (($pageScripts ?? []) as $pageScript): ?>
    <?php $scriptPath = APP_ROOT . '/assets/js/' . $pageScript . '.js'; $scriptVersion = is_file($scriptPath) ? filemtime($scriptPath) : 1; ?>
    <script src="<?= BASE_URL ?>/assets/js/<?= htmlspecialchars($pageScript) ?>.js?v=<?= (int) $scriptVersion ?>"></script>
<?php endforeach; ?>

</body>

</html>
