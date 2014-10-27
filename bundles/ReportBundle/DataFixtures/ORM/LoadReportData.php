<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ReportBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Mautic\CoreBundle\Helper\CsvHelper;
use Mautic\ReportBundle\Entity\Report;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadReportData
 */
class LoadReportData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $factory = $this->container->get('mautic.factory');
        $repo    = $factory->getModel('report')->getRepository();
        $reports = CsvHelper::csv_to_array(__DIR__ . '/fakereportdata.csv');
        foreach ($reports as $count => $rows) {
            $report = new Report();
            $key    = $count + 1;
            foreach ($rows as $col => $val) {
                if ($val != "NULL") {
                    $setter = "set" . ucfirst($col);
                    if (in_array($col, array('columns', 'filters'))) {
                        $val = unserialize(stripslashes($val));
                    }
                    $report->$setter($val);
                }
            }
            $repo->saveEntity($report);

            $this->setReference('report-' . $key, $report);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 7;
    }
}
