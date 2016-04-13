<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('mauticContent', 'notification');

$notificationType = 'list';

$header = ($notification->getId()) ?
    $view['translator']->trans('mautic.notification.header.edit',
        array('%name%' => $notification->getName())) :
    $view['translator']->trans('mautic.notification.header.new');

$view['slots']->set("headerTitle", $header);

if (!isset($attachmentSize)) {
    $attachmentSize = 0;
}
?>

<?php echo $view['form']->start($form); ?>
<div class="box-layout">
    <div class="col-md-9 height-auto bg-white">
        <div class="row">
            <div class="col-xs-12">
                <!-- tabs controls -->
                <ul class="bg-auto nav nav-tabs pr-md pl-md">
                    <li class="active"><a href="#notification-container" role="tab" data-toggle="tab"><?php echo $view['translator']->trans('mautic.notification.notification'); ?></a></li>
                    <li class=""><a href="#advanced-container" role="tab" data-toggle="tab"><?php echo $view['translator']->trans('mautic.core.advanced'); ?></a></li>
                </ul>
                <!--/ tabs controls -->
                <div class="tab-content pa-md">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 bg-white height-auto bdr-l">
        <div class="pr-lg pl-lg pt-md pb-md">
            <?php echo $view['form']->row($form['name']); ?>
            <div id="leadList"<?php echo ($notificationType == 'template') ? ' class="hide"' : ''; ?>>
                <?php echo $view['form']->row($form['lists']); ?>
            </div>
            <?php echo $view['form']->row($form['category']); ?>
            <?php echo $view['form']->row($form['language']); ?>
            <div id="publishStatus"<?php echo ($notificationType == 'list') ? ' class="hide"' : ''; ?>>
                <?php echo $view['form']->row($form['isPublished']); ?>
                <?php echo $view['form']->row($form['publishUp']); ?>
                <?php echo $view['form']->row($form['publishDown']); ?>
            </div>

            <?php echo $view['form']->rest($form); ?>
        </div>
    </div>
</div>
<?php echo $view['form']->end($form); ?>

<?php
$type = $notificationType;
if (empty($type) || !empty($forceTypeSelection)):
    echo $view->render('MauticCoreBundle:Helper:form_selecttype.html.php',
        array(
            'item'               => $notification,
            'mauticLang'         => array(
                'newListNotification' => 'mautic.notification.type.list.header',
                'newTemplateNotification'   => 'mautic.notification.type.template.header'
            ),
            'typePrefix'         => 'notification',
            'cancelUrl'          => 'mautic_notification_index',
            'header'             => 'mautic.notification.type.header',
            'typeOneHeader'      => 'mautic.notification.type.template.header',
            'typeOneIconClass'   => 'fa-cube',
            'typeOneDescription' => 'mautic.notification.type.template.description',
            'typeOneOnClick'     => "Mautic.selectNotificationType('template');",
            'typeTwoHeader'      => 'mautic.notification.type.list.header',
            'typeTwoIconClass'   => 'fa-list',
            'typeTwoDescription' => 'mautic.notification.type.list.description',
            'typeTwoOnClick'     => "Mautic.selectNotificationType('list');",
        ));
endif;