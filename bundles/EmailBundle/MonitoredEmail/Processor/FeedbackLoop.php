<?php

/*
 * @copyright   2017 Mautic Contributors. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\EmailBundle\MonitoredEmail\Processor;

use Mautic\EmailBundle\MonitoredEmail\Exception\FeedbackLoopNotFound;
use Mautic\EmailBundle\MonitoredEmail\Message;
use Mautic\EmailBundle\MonitoredEmail\Processor\FeedbackLoop\Parser;
use Mautic\EmailBundle\MonitoredEmail\Search\ContactFinder;
use Mautic\LeadBundle\Entity\DoNotContact;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class FeedbackLoop implements ProcessorInterface
{
    /**
     * @var ContactFinder
     */
    private $contactFinder;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Message
     */
    private $message;

    /**
     * @var \Mautic\LeadBundle\Model\DoNotContact
     */
    private $doNotContact;

    /**
     * FeedbackLoop constructor.
     */
    public function __construct(
        ContactFinder $contactFinder,
        TranslatorInterface $translator,
        LoggerInterface $logger,
        \Mautic\LeadBundle\Model\DoNotContact $doNotContact
    ) {
        $this->contactFinder = $contactFinder;
        $this->translator    = $translator;
        $this->logger        = $logger;
        $this->doNotContact  = $doNotContact;
    }

    /**
     * @return bool
     */
    public function process(Message $message)
    {
        $this->message = $message;
        $this->logger->debug('MONITORED EMAIL: Processing message ID '.$this->message->id.' for a feedback loop report');

        if (!$this->isApplicable()) {
            return false;
        }

        try {
            $parser = new Parser($this->message);
            if (!$contactEmail = $parser->parse()) {
                // A contact email was not found in the FBL report
                return false;
            }
        } catch (FeedbackLoopNotFound $exception) {
            return false;
        }

        $this->logger->debug('MONITORED EMAIL: Found '.$contactEmail.' in feedback loop report');

        $searchResult = $this->contactFinder->find($contactEmail);
        if (!$contacts = $searchResult->getContacts()) {
            return false;
        }

        $comments = $this->translator->trans('mautic.email.bounce.reason.spam');
        foreach ($contacts as $contact) {
            $this->doNotContact->addDncForContact($contact, 'email', $comments, DoNotContact::UNSUBSCRIBED);
        }

        return true;
    }

    /**
     * @return int
     */
    protected function isApplicable()
    {
        return preg_match('/.*feedback-type: abuse.*/is', $this->message->fblReport);
    }
}
