
<url>
    <loc>{concat($site_url, $node.url|ezurl('no'))}</loc>
    <news:news>
        <news:publication>
	    <news:name>Planète Santé</news:name>
	    <news:language>fr</news:language>
	</news:publication>
	<news:genres>Blog</news:genres>
	{if $node.data_map.publish_date.has_content}
	
	<news:publication_date>{$node.data_map.publish_date.data_int|datetime('custom', '%Y-%m-%d')}</news:publication_date>
	{else}
	
	<news:publication_date>{$node.object.published|datetime('custom', '%Y-%m-%d')}</news:publication_date>
	{/if}
	{if and(is_set($node.data_map.head_title), $node.data_map.head_title.has_content)}
	
    	<news:title>{$node.data_map.head_title.content|wash}<news:title>
	{else}
	
    	<news:title>{$node.name|wash}</news:title>
	{/if}
	{if and(is_set($node.data_map.meta_keywords), $node.data_map.meta_keywords.has_content)}
	
	<news:keywords>{$node.data_map.meta_keywords.content|wash}</news:keywords>
	{/if}
	
    </news:news>
</url>