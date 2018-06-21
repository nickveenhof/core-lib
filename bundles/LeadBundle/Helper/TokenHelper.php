<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Helper;

use Mautic\CoreBundle\Helper\DateTimeHelper;
use Mautic\CoreBundle\Templating\Helper\DateHelper;

/**
 * Class TokenHelper.
 */
class TokenHelper
{
    /**
     * @var DateHelper
     */
    private $dateHelper;

    /**
     * TokenHelper constructor.
     *
     * @param DateHelper $dateHelper
     */
    public function __construct(DateHelper $dateHelper)
    {
        $this->dateHelper = $dateHelper;
    }

    public function findContactTokens($content, $lead, $replace)
    {
        //$this->dateHelper->getTimeFormat();
    }

    /**
     * @param string $content
     * @param array  $lead
     * @param bool   $replace If true, search/replace will be executed on $content and the modified $content returned
     *                        rather than an array of found matches
     *
     * @return array|string
     */
    public static function findLeadTokens($content, $lead, $replace = false)
    {
        if (!$lead) {
            return $replace ? $content : [];
        }
        // Search for bracket or bracket encoded
        // @deprecated BC support for leadfield
        $tokenRegex = [
            '/({|%7B)leadfield=(.*?)(}|%7D)/',
            '/({|%7B)contactfield=(.*?)(}|%7D)/',
        ];
        $tokenList  = [];

        foreach ($tokenRegex as $regex) {
            $foundMatches = preg_match_all($regex, $content, $matches);
            if ($foundMatches) {
                foreach ($matches[2] as $key => $match) {
                    $token = $matches[0][$key];

                    if (isset($tokenList[$token])) {
                        continue;
                    }

                    $alias             = self::getFieldAlias($match);
                    $defaultValue      = self::getTokenDefaultValue($match);
                    $tokenList[$token] = self::getTokenValue($lead, $alias, $defaultValue, $match);
                }

                if ($replace) {
                    $content = str_replace(array_keys($tokenList), $tokenList, $content);
                }
            }
        }

        return $replace ? $content : $tokenList;
    }

    /**
     * Returns correct token value from provided list of tokens and the concrete token.
     *
     * @param array  $tokens like ['{contactfield=website}' => 'https://mautic.org']
     * @param string $token  like '{contactfield=website|https://default.url}'
     *
     * @return string empty string if no match
     */
    public static function getValueFromTokens(array $tokens, $token)
    {
        $token   = str_replace(['{', '}'], '', $token);
        $alias   = self::getFieldAlias($token);
        $default = self::getTokenDefaultValue($token);

        return empty($tokens["{{$alias}}"]) ? $default : $tokens["{{$alias}}"];
    }

    /**
     * @param array $lead
     * @param       $alias
     * @param       $defaultValue
     * @param array $match
     *
     * @return mixed
     */
    private static function getTokenValue(array $lead, $alias, $defaultValue, $match = [])
    {
        $value = '';
        if (isset($lead[$alias])) {
            $value = $lead[$alias];
        } elseif (isset($lead['companies'][0][$alias])) {
            $value = $lead['companies'][0][$alias];
        }
        if ('true' === $defaultValue) {
            $value = urlencode($value);
        } elseif (false !== strpos($defaultValue, 'date_format') and count(explode('|', $match)) == 3) {
            $dt    = new DateTimeHelper($value);
            $value = $dt->getDateTime()->format(explode('|', $match)[2]);
        }

        return $value ?: $defaultValue;
    }

    /**
     * @param $match
     * @param $urlencode
     *
     * @return string
     */
    private static function getTokenDefaultValue($match)
    {
        $fallbackCheck = explode('|', $match);
        if (!isset($fallbackCheck[1])) {
            return '';
        }

        return $fallbackCheck[1];
    }

    /**
     * @param $match
     *
     * @return mixed
     */
    private static function getFieldAlias($match)
    {
        $fallbackCheck = explode('|', $match);

        return $fallbackCheck[0];
    }
}
