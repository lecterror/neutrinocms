<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<?php foreach ($articles as $article): ?>
    <url>
		<loc><?php echo Router::url(
			array(
				'controller' => 'articles',
				'action' => 'view'), true); ?>/<?php echo $article['Article']['slug']; ?></loc>
		<lastmod><?php echo $time->toAtom($article['Article']['updated']); ?></lastmod>
		<changefreq>weekly</changefreq>
	</url>
	<?php endforeach; ?>

	<?php foreach ($downloads as $download): ?>
    <url>
		<loc><?php echo Router::url(
			array(
				'controller' => 'downloads',
				'action' => 'view'), true); ?>/<?php echo $download['Download']['slug']; ?></loc>
		<lastmod><?php echo $time->toAtom($download['Download']['created']); ?></lastmod>
		<changefreq>weekly</changefreq>
	</url>
	<?php endforeach; ?>
</urlset>