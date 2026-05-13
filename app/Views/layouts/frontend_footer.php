    <footer>
        <div class="footer-container">
            <div class="footer-info">
                <p>&copy; <?= date('Y') ?> <?= \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS') ?>. Hak Cipta Dilindungi.</p>
            </div>
            <div class="footer-credit">
                Dikembangkan dengan <span>PHP Native OOP</span> & <span>MySQL</span>
            </div>
        </div>
    </footer>

</body>
</html>
