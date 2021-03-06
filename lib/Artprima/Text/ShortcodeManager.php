<?php

namespace Artprima\Text;

class ShortcodeManager
{
    private $shortcodes = array();
    
    public function getShortcodes()
    {
        return $this->shortcodes;
    }
    
    public function registerShortcode(Shortcode $shortcode)
    {
        $this->shortcodes[$shortcode->getTag()] = $shortcode;
        $shortcode->setManager($this);
    }
    
    public function unregisterShortcode($tag)
    {
        $this->shortcodes[$tag]->setManager(null);
        unset($this->shortcodes[$tag]);
    }
    
    public function isShortcodeRegistered($tag)
    {
        return array_key_exists($tag, $this->shortcodes);
    }
    
    public function hasShortcode($content, $tag) {
        if ($this->isShortcodeRegistered($tag)) {
            preg_match_all( '/' . $this->getShortcodeRegex() . '/s', $content, $matches, PREG_SET_ORDER );
            if (empty($matches)) {
                return false;
            }

            foreach ($matches as $shortcode) {
                if ($tag === $shortcode[2]) {
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * Search content for shortcodes and filter shortcodes through their hooks.
     *
     * If there are no shortcode tags defined, then the content will be returned
     * without any filtering. This might cause issues when plugins are disabled but
     * the shortcode will still show up in the post or content.
     *
     * @param string $content Content to search for shortcodes
     * @return string Content with shortcodes filtered out.
     */
    public function applyShortcodes($content)
    {
        if (empty($this->shortcodes) || !is_array($this->shortcodes)) {
            return $content;
        }

        $pattern = $this->getShortcodeRegex();
        return preg_replace_callback("/$pattern/s", array($this, 'applyShortcode'), $content);
    }
    
    /**
     * Regular Expression callable for do_shortcode() for calling shortcode hook.
     * @see get_shortcode_regex for details of the match array contents.
     *
     * @param array $m Regular expression match array
     * @return mixed False on failure.
     */
    public function applyShortcode($m)
    {
        // allow [[foo]] syntax for escaping a tag
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = shortcode_parse_atts($m[3]);

        if (isset($m[5])) {
            // enclosing tag - extra parameter
            return $m[1] . $this->shortcodes[$tag]->apply($attr, $m[5]) . $m[6];
        } else {
            // self-closing tag
            return $m[1] . $this->shortcodes[$tag]->apply($attr) . $m[6];
        }
    }
    
    /**
     * Retrieve all attributes from the shortcodes tag.
     *
     * The attributes list has the attribute name as the key and the value of the
     * attribute as the value in the key/value pair. This allows for easier
     * retrieval of the attributes, since all attributes have to be known.
     *
     * @param string $text
     * @return array List of attributes and their value.
     */
    public function parseShortcodeAttrs($text) {
        $atts = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
            foreach ($match as $m) {
                if (!empty($m[1]))
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5]))
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) and strlen($m[7]))
                    $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8]))
                    $atts[] = stripcslashes($m[8]);
            }
        } else {
            $atts = ltrim($text);
        }
        return $atts;
    }
    
    
    /**
     * Retrieve the shortcode regular expression for searching.
     *
     * The regular expression combines the shortcode tags in the regular expression
     * in a regex class.
     *
     * The regular expression contains 6 different sub matches to help with parsing.
     *
     * 1 - An extra [ to allow for escaping shortcodes with double [[]]
     * 2 - The shortcode name
     * 3 - The shortcode argument list
     * 4 - The self closing /
     * 5 - The content of a shortcode when it wraps some content.
     * 6 - An extra ] to allow for escaping shortcodes with double [[]]
     *
     * @return string The shortcode search regular expression
     */
    public function getShortcodeRegex() {
        $tagnames = array_keys($this->shortcodes);
        $tagregexp = join( '|', array_map('preg_quote', $tagnames) );

        // WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
        // Also, see shortcode_unautop() and shortcode.js.
        return
              '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }
}