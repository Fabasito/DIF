<?php
require_once __DIR__ . '/inc/data.php';
header('Content-Type: application/xml; charset=UTF-8');
$base = site_base_url();

$pages = ['index.php','le-master.php','formation.php','admissions.php','reseau.php','actualites.php','contact.php'];
$actus = array_values(array_filter(load_json('actualites', []), 'is_published'));

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($pages as $p) {
    $loc = $base . '/' . ($p === 'index.php' ? '' : $p);
    echo "  <url><loc>" . e($loc) . "</loc><changefreq>monthly</changefreq></url>\n";
}
foreach ($actus as $a) {
    $loc = $base . '/article.php?slug=' . rawurlencode(article_slug($a));
    $lastmod = !empty($a['date']) ? date('Y-m-d', strtotime($a['date'])) : '';
    echo "  <url><loc>" . e($loc) . "</loc>" . ($lastmod ? "<lastmod>$lastmod</lastmod>" : '') . "<changefreq>yearly</changefreq></url>\n";
}
echo "</urlset>\n";
