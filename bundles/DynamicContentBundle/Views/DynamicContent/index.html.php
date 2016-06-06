<?php
/**
 * @package     Mautic
 * @copyright   2016 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

$view->extend('MauticCoreBundle:Default:content.html.php');

$view['slots']->set('mauticContent', 'dynamicContent');
$view['slots']->set("headerTitle", $view['translator']->trans('mautic.dynamicContent.dynamicContents'));

$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:page_actions.html.php',
        [
            'templateButtons' => [
                'new' => $permissions['dynamicContent:dynamicContents:create']
            ],
            'routeBase' => 'dwc'
        ]
    )
);
?>

<div class="panel panel-default bdr-t-wdh-0 mb-0">
    <?php echo $view->render('MauticCoreBundle:Helper:list_toolbar.html.php', array(
        'searchValue' => $searchValue,
        'searchHelp'  => 'mautic.page.help.searchcommands',
        'action'      => $currentRoute,
        'routeBase'   => 'dwc',
        'templateButtons' => array(
            'delete' => $permissions['dynamicContent:dynamicContents:deleteown'] || $permissions['dynamicContent:dynamicContents:deleteother']
        )
    )); ?>
    <div class="page-list">
        <?php $view['slots']->output('_content'); ?>
    </div>
</div>
