<?php


$contentIni = eZINI::instance('content.ini');
$ini = eZINI::instance('nxc_news_sitemap.ini');
$tpl = eZTemplate::factory();

$ParentNodeId = $contentIni->variable('NodeSettings', 'RootNode');
$ClassFilterType = $ini->variable('Classes','Class_Filter_Type');
$ClassFilterArray = $ini->variable('Classes','Class_Filter_Array');
$MainNodeOnly = true;
$siteUrl = $ini->variable('SiteSettings', 'SiteURL');
$limit = 20;
$offset = 0;
$depth = 20;
$ExtendedAttributeFilter = array(
        'id'     => 'nxc_few_checkbox',
        'params' => array(
            'classes'     => $ClassFilterArray,
            'attribute' => 'news_sitemap_include',
            'cond' => array(
                'ezcontentobject_attribute.data_int',
                '=',
                1
            )
        )
);
$params = array(
            'Depth'           => $depth,
            'Limit'           => $limit,
            'Offset'          => $offset,
            'LoadDataMap'     => true,
            'ClassFilterType' => $ClassFilterType,
            'ClassFilterArray'=> $ClassFilterArray,
            'MainNodeOnly'    => $MainNodeOnly,
            'ExtendedAttributeFilter' => $ExtendedAttributeFilter
);
$result =
'<?xml version="1.0" encoding="UTF-8"?>
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
    xsi:schemaLocation="
	http://www.sitemaps.org/schemas/sitemap/0.9
	http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
$user = eZUser::fetch(14);
if ( $user ) {
    eZUser::setCurrentlyLoggedInUser( $user, $user->attribute( 'id' ) );
} else {
    $cli->error( 'Could not fetch admin user object' );
}


do {
    $nodes = array();
    $nodes = eZContentObjectTreeNode::subTreeByNodeID($params, $ParentNodeId);
    $params['Offset'] += $limit;
    foreach( $nodes as $key => $node ) {
        $tpl->resetVariables();
        $tpl->setVariable('node', $node);
        $tpl->setVariable('site_url', $siteUrl);
        $result .= $tpl->fetch('design:nxc_news_sitemap/nxc_news_item.tpl');
        $object = $node->attribute( 'object' );
        eZContentObject::clearCache( $object->attribute( 'id' ) );
        $object->resetDataMap();
    }
    $cli->output($params['Offset']);
} while (count($nodes));
$result .= "</urlset>";
eZFile::create('sitemap-news.xml', './var/storage/', $result);
$cli->output('The google news sitemap for mfa was successfuly updated: ' . date('l dS of F Y h:i:s A'));

eZExecution::cleanExit();

?>
