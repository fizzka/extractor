<?php

/**
 * extracted from olamedia nokogiri, @see https://github.com/olamedia/nokogiri
 */
class XpathSubquery
{
    const REGEXP = '/(?P<tag>[a-z\d]+)?(\[(?P<attr>\S+)(=(?P<value>[^\]]+))?\])?'
        . '(#(?P<id>[^\s:>#\.]+))?(\.(?P<class>[^\s:>#\.]+))?'
        . '(:(?P<pseudo>(first|last|nth)-child)(\((?P<expr>[^\)]+)\))?)?\s*(?P<rel>>)?/isS';

    protected static $compiledXpath = [];

    public static function get($expression, $rel = false, $compile = true)
    {
        $key = $expression . ($rel ? '>' : '*');
        if ($compile && isset(self::$compiledXpath[$key])) {
            return self::$compiledXpath[$key];
        }

        $query = self::buildQuery($expression, $rel, $compile);

        if ($compile) {
            self::$compiledXpath[$key] = $query;
        }

        return $query;
    }

    private static function buildQuery($expression, $rel, $compile)
    {
        if (!preg_match(self::REGEXP, $expression, $subs)) {
            return;
        }

        $brackets = [];
        if (isset($subs['id']) && '' !== $subs['id']) {
            $brackets[] = "@id='" . $subs['id'] . "'";
        }
        if (isset($subs['attr']) && '' !== $subs['attr']) {
            if (!(isset($subs['value']))) {
                $brackets[] = "@" . $subs['attr'];
            } else {
                $attrValue = !empty($subs['value']) ? $subs['value'] : '';
                $brackets[] = "@" . $subs['attr'] . "='" . $attrValue . "'";
            }
        }
        if (isset($subs['class']) && '' !== $subs['class']) {
            $brackets[] = 'contains(concat(" ", normalize-space(@class), " "), " ' . $subs['class'] . ' ")';
        }
        if (isset($subs['pseudo']) && '' !== $subs['pseudo']) {
            if ('first-child' === $subs['pseudo']) {
                $brackets[] = '1';
            } elseif ('last-child' === $subs['pseudo']) {
                $brackets[] = 'last()';
            } elseif ('nth-child' === $subs['pseudo']) {
                if (isset($subs['expr']) && '' !== $subs['expr']) {
                    $e = $subs['expr'];
                    if ('odd' === $e) {
                        $brackets[] = '(position() -1) mod 2 = 0 and position() >= 1';
                    } elseif ('even' === $e) {
                        $brackets[] = 'position() mod 2 = 0 and position() >= 0';
                    } elseif (preg_match("/^\d+$/", $e)) {
                        $brackets[] = 'position() = ' . $e;
                    } elseif (preg_match("/^((?P<mul>\d+)n\+)(?P<pos>\d+)$/is", $e, $esubs)) {
                        if (isset($esubs['mul'])) {
                            $brackets[] = '(position() -' . $esubs['pos'] . ') mod '
                                . $esubs['mul'] . ' = 0 and position() >= ' . $esubs['pos'] . '';
                        } else {
                            $brackets[] = $e;
                        }
                    }
                }
            }
        }

        $query = ($rel ? '/' : '//')
            . ((isset($subs['tag']) && '' !== $subs['tag']) ? $subs['tag'] : '*')
            . (($c = count($brackets))
                ? ($c > 1 ? '[(' . implode(') and (', $brackets) . ')]' : '[' . implode(' and ', $brackets) . ']')
                : '');

        $left = trim(substr($expression, strlen($subs[0])));
        if ('' !== $left) {
            $query .= self::get($left, isset($subs['rel']) ? '>' === $subs['rel'] : false, $compile);
        }

        return $query;
    }
}
