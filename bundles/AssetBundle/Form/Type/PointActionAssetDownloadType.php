<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\AssetBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PointActionFormSubmitType
 *
 * @package Mautic\AssetBundle\Form\Type
 */
class PointActionAssetDownloadType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder->add('assets', 'asset_list', array(
            'expanded'      => false,
            'multiple'      => true,
            'label'         => 'mautic.asset.point.action.assets',
            'label_attr'    => array('class' => 'control-label'),
            'empty_value'   => false,
            'required'      => false,
            'attr'       => array(
                'class'   => 'form-control',
                'tooltip' => 'mautic.asset.point.action.assets.descr'
            )
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return "pointaction_assetdownload";
    }
}