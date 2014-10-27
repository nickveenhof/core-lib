<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
?>

<!-- timeline -->
<ul class="timeline">
    <li class="header ellipsis bg-white">Recent Events</li>
    <li class="wrapper">
        <ul class="events">
            <?php foreach ($events as $event) : ?>
                <?php if (isset($event['contentTemplate'])) : ?>
                    <?php echo $view->render($event['contentTemplate'], array('event' => $event)); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </li>
</ul>
<!--/ timeline -->