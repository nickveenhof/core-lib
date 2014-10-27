<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Mautic\CoreBundle\Controller\CommonController;
use Symfony\Component\Intl\Intl;

/**
 * Class DefaultController
 *
 * @package Mautic\DashboardBundle\Controller
 */
class DefaultController extends CommonController
{

    /**
     * Generates the default view
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
    	$model = $this->factory->getModel('dashboard.dashboard');

        $sentReadCount = $this->factory->getModel('email.email')->getRepository()->getSentReadCount();
        $newReturningVisitors = $this->factory->getEntityManager()->getRepository('MauticPageBundle:Hit')->getNewReturningVisitorsCount();
        $weekVisitors = $this->factory->getEntityManager()->getRepository('MauticPageBundle:Hit')->countVisitors(604800);
        $allTimeVisitors = $this->factory->getEntityManager()->getRepository('MauticPageBundle:Hit')->countVisitors(0);
        $allSentEmails = $this->factory->getEntityManager()->getRepository('MauticEmailBundle:Stat')->getSentCount();
        $popularPages = $this->factory->getModel('page.page')->getRepository()->getPopularPages();
        $popularAssets = $this->factory->getModel('asset.asset')->getRepository()->getPopularAssets();
        $popularCampaigns = $this->factory->getModel('campaign.campaign')->getRepository()->getPopularCampaigns();

        $openRate = 0;

        if ($sentReadCount['sentCount']) {
            $openRate = round($sentReadCount['readCount'] / $sentReadCount['sentCount'] * 100);
        }

        $clickRate = 0;

        if ($sentReadCount['readCount']) {
            $clickRate = round($sentReadCount['clickCount'] / $sentReadCount['readCount'] * 100);
        }

        $countries = array_flip(Intl::getRegionBundle()->getCountryNames());
        $mapData = array();

        /** @var \Mautic\LeadBundle\Entity\LeadRepository $leadRepository */
        $leadRepository = $this->factory->getEntityManager()->getRepository('MauticLeadBundle:Lead');
        $leadCountries = $leadRepository->getLeadsCountPerCountries();

        // Convert country names to 2-char code
        foreach ($leadCountries as $leadCountry) {
            if (isset($countries[$leadCountry['country']])) {
                $mapData[strtolower($countries[$leadCountry['country']])] = $leadCountry['quantity'];
            }
        }

        return $this->delegateView(array(
            'viewParameters'  =>  array(
                'sentReadCount'     => $sentReadCount,
                'openRate'          => $openRate,
                'clickRate'         => $clickRate,
                'newReturningVisitors' => $newReturningVisitors,
                'weekVisitors'      => $weekVisitors,
                'allTimeVisitors'   => $allTimeVisitors,
                'popularPages'      => $popularPages,
                'popularAssets'     => $popularAssets,
                'popularCampaigns'  => $popularCampaigns,
                'allSentEmails'     => $allSentEmails,
                'mapData'           => $mapData,
                'security'          => $this->factory->getSecurity()
            ),
            'contentTemplate' => 'MauticDashboardBundle:Default:index.html.php',
            'passthroughVars' => array(
                'activeLink'     => '#mautic_dashboard_index',
                'mauticContent'  => 'dashboard'
            )
        ));
    }
}
