<?php
/**
 Admin Page Framework v3.5.10b by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
class TextTruncator_AdminPageFramework_Parsedown {
    function text($text) {
        $this->Definitions = array();
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\r", "\n", $text);
        $text = trim($text, "\n");
        $lines = explode("\n", $text);
        $markup = $this->lines($lines);
        $markup = trim($markup, "\n");
        return $markup;
    }
    private $breaksEnabled;
    function setBreaksEnabled($breaksEnabled) {
        $this->breaksEnabled = $breaksEnabled;
        return $this;
    }
    private $markupEscaped;
    function setMarkupEscaped($markupEscaped) {
        $this->markupEscaped = $markupEscaped;
        return $this;
    }
    private $urlsLinked = true;
    function setUrlsLinked($urlsLinked) {
        $this->urlsLinked = $urlsLinked;
        return $this;
    }
    protected $BlockTypes = array('#' => array('Header'), '*' => array('Rule', 'List'), '+' => array('List'), '-' => array('SetextHeader', 'Table', 'Rule', 'List'), '0' => array('List'), '1' => array('List'), '2' => array('List'), '3' => array('List'), '4' => array('List'), '5' => array('List'), '6' => array('List'), '7' => array('List'), '8' => array('List'), '9' => array('List'), ':' => array('Table'), '<' => array('Comment', 'Markup'), '=' => array('SetextHeader'), '>' => array('Quote'), '_' => array('Rule'), '`' => array('FencedCode'), '|' => array('Table'), '~' => array('FencedCode'),);
    protected $DefinitionTypes = array('[' => array('Reference'),);
    protected $unmarkedBlockTypes = array('Code',);
    private function lines(array $lines) {
        $CurrentBlock = null;
        foreach ($lines as $line) {
            if (chop($line) === '') {
                if (isset($CurrentBlock)) {
                    $CurrentBlock['interrupted'] = true;
                }
                continue;
            }
            if (strpos($line, "\t") !== false) {
                $parts = explode("\t", $line);
                $line = $parts[0];
                unset($parts[0]);
                foreach ($parts as $part) {
                    $shortage = 4 - mb_strlen($line, 'utf-8') % 4;
                    $line.= str_repeat(' ', $shortage);
                    $line.= $part;
                }
            }
            $indent = 0;
            while (isset($line[$indent]) and $line[$indent] === ' ') {
                $indent++;
            }
            $text = $indent > 0 ? substr($line, $indent) : $line;
            $Line = array('body' => $line, 'indent' => $indent, 'text' => $text);
            if (isset($CurrentBlock['incomplete'])) {
                $Block = $this->{'block' . $CurrentBlock['type'] . 'Continue'}($Line, $CurrentBlock);
                if (isset($Block)) {
                    $CurrentBlock = $Block;
                    continue;
                } else {
                    if (method_exists($this, 'block' . $CurrentBlock['type'] . 'Complete')) {
                        $CurrentBlock = $this->{'block' . $CurrentBlock['type'] . 'Complete'}($CurrentBlock);
                    }
                    unset($CurrentBlock['incomplete']);
                }
            }
            $marker = $text[0];
            if (isset($this->DefinitionTypes[$marker])) {
                foreach ($this->DefinitionTypes[$marker] as $definitionType) {
                    $Definition = $this->{'definition' . $definitionType}($Line, $CurrentBlock);
                    if (isset($Definition)) {
                        $this->Definitions[$definitionType][$Definition['id']] = $Definition['data'];
                        continue 2;
                    }
                }
            }
            $blockTypes = $this->unmarkedBlockTypes;
            if (isset($this->BlockTypes[$marker])) {
                foreach ($this->BlockTypes[$marker] as $blockType) {
                    $blockTypes[] = $blockType;
                }
            }
            foreach ($blockTypes as $blockType) {
                $Block = $this->{'block' . $blockType}($Line, $CurrentBlock);
                if (isset($Block)) {
                    $Block['type'] = $blockType;
                    if (!isset($Block['identified'])) {
                        $Elements[] = $CurrentBlock['element'];
                        $Block['identified'] = true;
                    }
                    if (method_exists($this, 'block' . $blockType . 'Continue')) {
                        $Block['incomplete'] = true;
                    }
                    $CurrentBlock = $Block;
                    continue 2;
                }
            }
            if (isset($CurrentBlock) and !isset($CurrentBlock['type']) and !isset($CurrentBlock['interrupted'])) {
                $CurrentBlock['element']['text'].= "\n" . $text;
            } else {
                $Elements[] = $CurrentBlock['element'];
                $CurrentBlock = $this->paragraph($Line);
                $CurrentBlock['identified'] = true;
            }
        }
        if (isset($CurrentBlock['incomplete']) and method_exists($this, 'block' . $CurrentBlock['type'] . 'Complete')) {
            $CurrentBlock = $this->{'block' . $CurrentBlock['type'] . 'Complete'}($CurrentBlock);
        }
        $Elements[] = $CurrentBlock['element'];
        unset($Elements[0]);
        $markup = $this->elements($Elements);
        return $markup;
    }
    protected function blockCode($Line, $Block = null) {
        if (isset($Block) and !isset($Block['type']) and !isset($Block['interrupted'])) {
            return;
        }
        if ($Line['indent'] >= 4) {
            $text = substr($Line['body'], 4);
            $Block = array('element' => array('name' => 'pre', 'handler' => 'element', 'text' => array('name' => 'code', 'text' => $text,),),);
            return $Block;
        }
    }
    protected function blockCodeContinue($Line, $Block) {
        if ($Line['indent'] >= 4) {
            if (isset($Block['interrupted'])) {
                $Block['element']['text']['text'].= "\n";
                unset($Block['interrupted']);
            }
            $Block['element']['text']['text'].= "\n";
            $text = substr($Line['body'], 4);
            $Block['element']['text']['text'].= $text;
            return $Block;
        }
    }
    protected function blockCodeComplete($Block) {
        $text = $Block['element']['text']['text'];
        $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
        $Block['element']['text']['text'] = $text;
        return $Block;
    }
    protected function blockComment($Line) {
        if ($this->markupEscaped) {
            return;
        }
        if (isset($Line['text'][3]) and $Line['text'][3] === '-' and $Line['text'][2] === '-' and $Line['text'][1] === '!') {
            $Block = array('element' => array('text' => $Line['body'],),);
            if (preg_match('/-->$/', $Line['text'])) {
                $Block['closed'] = true;
            }
            return $Block;
        }
    }
    protected function blockCommentContinue($Line, array $Block) {
        if (isset($Block['closed'])) {
            return;
        }
        $Block['element']['text'].= "\n" . $Line['body'];
        if (preg_match('/-->$/', $Line['text'])) {
            $Block['closed'] = true;
        }
        return $Block;
    }
    protected function blockFencedCode($Line) {
        if (preg_match('/^([' . $Line['text'][0] . ']{3,})[ ]*([\w-]+)?[ ]*$/', $Line['text'], $matches)) {
            $Element = array('name' => 'code', 'text' => '',);
            if (isset($matches[2])) {
                $class = 'language-' . $matches[2];
                $Element['attributes'] = array('class' => $class,);
            }
            $Block = array('char' => $Line['text'][0], 'element' => array('name' => 'pre', 'handler' => 'element', 'text' => $Element,),);
            return $Block;
        }
    }
    protected function blockFencedCodeContinue($Line, $Block) {
        if (isset($Block['complete'])) {
            return;
        }
        if (isset($Block['interrupted'])) {
            $Block['element']['text']['text'].= "\n";
            unset($Block['interrupted']);
        }
        if (preg_match('/^' . $Block['char'] . '{3,}[ ]*$/', $Line['text'])) {
            $Block['element']['text']['text'] = substr($Block['element']['text']['text'], 1);
            $Block['complete'] = true;
            return $Block;
        }
        $Block['element']['text']['text'].= "\n" . $Line['body'];;
        return $Block;
    }
    protected function blockFencedCodeComplete($Block) {
        $text = $Block['element']['text']['text'];
        $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
        $Block['element']['text']['text'] = $text;
        return $Block;
    }
    protected function blockHeader($Line) {
        if (isset($Line['text'][1])) {
            $level = 1;
            while (isset($Line['text'][$level]) and $Line['text'][$level] === '#') {
                $level++;
            }
            if ($level > 6 or $Line['text'][$level] !== ' ') {
                return;
            }
            $text = trim($Line['text'], '# ');
            $Block = array('element' => array('name' => 'h' . min(6, $level), 'text' => $text, 'handler' => 'line',),);
            return $Block;
        }
    }
    protected function blockList($Line) {
        list($name, $pattern) = $Line['text'][0] <= '-' ? array('ul', '[*+-]') : array('ol', '[0-9]+[.]');
        if (preg_match('/^(' . $pattern . '[ ]+)(.*)/', $Line['text'], $matches)) {
            $Block = array('indent' => $Line['indent'], 'pattern' => $pattern, 'element' => array('name' => $name, 'handler' => 'elements',),);
            $Block['li'] = array('name' => 'li', 'handler' => 'li', 'text' => array($matches[2],),);
            $Block['element']['text'][] = & $Block['li'];
            return $Block;
        }
    }
    protected function blockListContinue($Line, array $Block) {
        if ($Block['indent'] === $Line['indent'] and preg_match('/^' . $Block['pattern'] . '[ ]+(.*)/', $Line['text'], $matches)) {
            if (isset($Block['interrupted'])) {
                $Block['li']['text'][] = '';
                unset($Block['interrupted']);
            }
            unset($Block['li']);
            $Block['li'] = array('name' => 'li', 'handler' => 'li', 'text' => array($matches[1],),);
            $Block['element']['text'][] = & $Block['li'];
            return $Block;
        }
        if (!isset($Block['interrupted'])) {
            $text = preg_replace('/^[ ]{0,4}/', '', $Line['body']);
            $Block['li']['text'][] = $text;
            return $Block;
        }
        if ($Line['indent'] > 0) {
            $Block['li']['text'][] = '';
            $text = preg_replace('/^[ ]{0,4}/', '', $Line['body']);
            $Block['li']['text'][] = $text;
            unset($Block['interrupted']);
            return $Block;
        }
    }
    protected function blockQuote($Line) {
        if (preg_match('/^>[ ]?(.*)/', $Line['text'], $matches)) {
            $Block = array('element' => array('name' => 'blockquote', 'handler' => 'lines', 'text' => (array)$matches[1],),);
            return $Block;
        }
    }
    protected function blockQuoteContinue($Line, array $Block) {
        if ($Line['text'][0] === '>' and preg_match('/^>[ ]?(.*)/', $Line['text'], $matches)) {
            if (isset($Block['interrupted'])) {
                $Block['element']['text'][] = '';
                unset($Block['interrupted']);
            }
            $Block['element']['text'][] = $matches[1];
            return $Block;
        }
        if (!isset($Block['interrupted'])) {
            $Block['element']['text'][] = $Line['text'];
            return $Block;
        }
    }
    protected function blockRule($Line) {
        if (preg_match('/^([' . $Line['text'][0] . '])([ ]*\1){2,}[ ]*$/', $Line['text'])) {
            $Block = array('element' => array('name' => 'hr'),);
            return $Block;
        }
    }
    protected function blockSetextHeader($Line, array $Block = null) {
        if (!isset($Block) or isset($Block['type']) or isset($Block['interrupted'])) {
            return;
        }
        if (chop($Line['text'], $Line['text'][0]) === '') {
            $Block['element']['name'] = $Line['text'][0] === '=' ? 'h1' : 'h2';
            return $Block;
        }
    }
    protected function blockMarkup($Line) {
        if ($this->markupEscaped) {
            return;
        }
        $attrName = '[a-zA-Z_:][\w:.-]*';
        $attrValue = '(?:[^"\'=<>`\s]+|".*?"|\'.*?\')';
        preg_match('/^<(\w[\d\w]*)((?:\s' . $attrName . '(?:\s*=\s*' . $attrValue . ')?)*)\s*(\/?)>/', $Line['text'], $matches);
        if (!$matches or in_array($matches[1], $this->textLevelElements)) {
            return;
        }
        $Block = array('depth' => 0, 'element' => array('name' => $matches[1], 'text' => null,),);
        $remainder = substr($Line['text'], strlen($matches[0]));
        if (trim($remainder) === '') {
            if ($matches[3] or in_array($matches[1], $this->voidElements)) {
                $Block['closed'] = true;
            }
        } else {
            if ($matches[3] or in_array($matches[1], $this->voidElements)) {
                return;
            }
            preg_match('/(.*)<\/' . $matches[1] . '>\s*$/i', $remainder, $nestedMatches);
            if ($nestedMatches) {
                $Block['closed'] = true;
                $Block['element']['text'] = $nestedMatches[1];
            } else {
                $Block['element']['text'] = $remainder;
            }
        }
        if (!$matches[2]) {
            return $Block;
        }
        preg_match_all('/\s(' . $attrName . ')(?:\s*=\s*(' . $attrValue . '))?/', $matches[2], $nestedMatches, PREG_SET_ORDER);
        foreach ($nestedMatches as $nestedMatch) {
            if (!isset($nestedMatch[2])) {
                $Block['element']['attributes'][$nestedMatch[1]] = '';
            } elseif ($nestedMatch[2][0] === '"' or $nestedMatch[2][0] === '\'') {
                $Block['element']['attributes'][$nestedMatch[1]] = substr($nestedMatch[2], 1, -1);
            } else {
                $Block['element']['attributes'][$nestedMatch[1]] = $nestedMatch[2];
            }
        }
        return $Block;
    }
    protected function blockMarkupContinue($Line, array $Block) {
        if (isset($Block['closed'])) {
            return;
        }
        if (preg_match('/^<' . $Block['element']['name'] . '(?:\s.*[\'"])?\s*>/i', $Line['text'])) {
            $Block['depth']++;
        }
        if (preg_match('/(.*?)<\/' . $Block['element']['name'] . '>\s*$/i', $Line['text'], $matches)) {
            if ($Block['depth'] > 0) {
                $Block['depth']--;
            } else {
                $Block['element']['text'].= "\n";
                $Block['closed'] = true;
            }
            $Block['element']['text'].= $matches[1];
        }
        if (isset($Block['interrupted'])) {
            $Block['element']['text'].= "\n";
            unset($Block['interrupted']);
        }
        if (!isset($Block['closed'])) {
            $Block['element']['text'].= "\n" . $Line['body'];
        }
        return $Block;
    }
    protected function blockTable($Line, array $Block = null) {
        if (!isset($Block) or isset($Block['type']) or isset($Block['interrupted'])) {
            return;
        }
        if (strpos($Block['element']['text'], '|') !== false and chop($Line['text'], ' -:|') === '') {
            $alignments = array();
            $divider = $Line['text'];
            $divider = trim($divider);
            $divider = trim($divider, '|');
            $dividerCells = explode('|', $divider);
            foreach ($dividerCells as $dividerCell) {
                $dividerCell = trim($dividerCell);
                if ($dividerCell === '') {
                    continue;
                }
                $alignment = null;
                if ($dividerCell[0] === ':') {
                    $alignment = 'left';
                }
                if (substr($dividerCell, -1) === ':') {
                    $alignment = $alignment === 'left' ? 'center' : 'right';
                }
                $alignments[] = $alignment;
            }
            $HeaderElements = array();
            $header = $Block['element']['text'];
            $header = trim($header);
            $header = trim($header, '|');
            $headerCells = explode('|', $header);
            foreach ($headerCells as $index => $headerCell) {
                $headerCell = trim($headerCell);
                $HeaderElement = array('name' => 'th', 'text' => $headerCell, 'handler' => 'line',);
                if (isset($alignments[$index])) {
                    $alignment = $alignments[$index];
                    $HeaderElement['attributes'] = array('align' => $alignment,);
                }
                $HeaderElements[] = $HeaderElement;
            }
            $Block = array('alignments' => $alignments, 'identified' => true, 'element' => array('name' => 'table', 'handler' => 'elements',),);
            $Block['element']['text'][] = array('name' => 'thead', 'handler' => 'elements',);
            $Block['element']['text'][] = array('name' => 'tbody', 'handler' => 'elements', 'text' => array(),);
            $Block['element']['text'][0]['text'][] = array('name' => 'tr', 'handler' => 'elements', 'text' => $HeaderElements,);
            return $Block;
        }
    }
    protected function blockTableContinue($Line, array $Block) {
        if ($Line['text'][0] === '|' or strpos($Line['text'], '|')) {
            $Elements = array();
            $row = $Line['text'];
            $row = trim($row);
            $row = trim($row, '|');
            $cells = explode('|', $row);
            foreach ($cells as $index => $cell) {
                $cell = trim($cell);
                $Element = array('name' => 'td', 'handler' => 'line', 'text' => $cell,);
                if (isset($Block['alignments'][$index])) {
                    $Element['attributes'] = array('align' => $Block['alignments'][$index],);
                }
                $Elements[] = $Element;
            }
            $Element = array('name' => 'tr', 'handler' => 'elements', 'text' => $Elements,);
            $Block['element']['text'][1]['text'][] = $Element;
            return $Block;
        }
    }
    protected function definitionReference($Line) {
        if (preg_match('/^\[(.+?)\]:[ ]*<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*$/', $Line['text'], $matches)) {
            $Definition = array('id' => strtolower($matches[1]), 'data' => array('url' => $matches[2], 'title' => null,),);
            if (isset($matches[3])) {
                $Definition['data']['title'] = $matches[3];
            }
            return $Definition;
        }
    }
    protected function paragraph($Line) {
        $Block = array('element' => array('name' => 'p', 'text' => $Line['text'], 'handler' => 'line',),);
        return $Block;
    }
    protected function element(array $Element) {
        $markup = '';
        if (isset($Element['name'])) {
            $markup.= '<' . $Element['name'];
            if (isset($Element['attributes'])) {
                foreach ($Element['attributes'] as $name => $value) {
                    if ($value === null) {
                        continue;
                    }
                    $markup.= ' ' . $name . '="' . $value . '"';
                }
            }
            if (isset($Element['text'])) {
                $markup.= '>';
            } else {
                $markup.= ' />';
                return $markup;
            }
        }
        if (isset($Element['text'])) {
            if (isset($Element['handler'])) {
                $markup.= $this->$Element['handler']($Element['text']);
            } else {
                $markup.= $Element['text'];
            }
        }
        if (isset($Element['name'])) {
            $markup.= '</' . $Element['name'] . '>';
        }
        return $markup;
    }
    protected function elements(array $Elements) {
        $markup = '';
        foreach ($Elements as $Element) {
            if ($Element === null) {
                continue;
            }
            $markup.= "\n" . $this->element($Element);
        }
        $markup.= "\n";
        return $markup;
    }
    protected $InlineTypes = array('"' => array('QuotationMark'), '!' => array('Image'), '&' => array('Ampersand'), '*' => array('Emphasis'), '<' => array('UrlTag', 'EmailTag', 'Tag', 'LessThan'), '>' => array('GreaterThan'), '[' => array('Link'), '_' => array('Emphasis'), '`' => array('Code'), '~' => array('Strikethrough'), '\\' => array('EscapeSequence'),);
    protected $inlineMarkerList = '!"*_&[<>`~\\';
    public function line($text) {
        $markup = '';
        $remainder = $text;
        $markerPosition = 0;
        while ($excerpt = strpbrk($remainder, $this->inlineMarkerList)) {
            $marker = $excerpt[0];
            $markerPosition+= strpos($remainder, $marker);
            foreach ($this->InlineTypes[$marker] as $inlineType) {
                $handler = 'inline' . $inlineType;
                $Inline = $this->$handler($excerpt);
                if (!isset($Inline)) {
                    continue;
                }
                $plainText = substr($text, 0, $markerPosition);
                $markup.= $this->unmarkedText($plainText);
                $markup.= isset($Inline['markup']) ? $Inline['markup'] : $this->element($Inline['element']);
                $text = substr($text, $markerPosition + $Inline['extent']);
                $remainder = $text;
                $markerPosition = 0;
                continue 2;
            }
            $remainder = substr($excerpt, 1);
            $markerPosition++;
        }
        $markup.= $this->unmarkedText($text);
        return $markup;
    }
    protected function inlineAmpersand($excerpt) {
        if (!preg_match('/^&#?\w+;/', $excerpt)) {
            return array('markup' => '&amp;', 'extent' => 1,);
        }
    }
    protected function inlineStrikethrough($excerpt) {
        if (!isset($excerpt[1])) {
            return;
        }
        if ($excerpt[1] === '~' and preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', $excerpt, $matches)) {
            return array('extent' => strlen($matches[0]), 'element' => array('name' => 'del', 'text' => $matches[1], 'handler' => 'line',),);
        }
    }
    protected function inlineEscapeSequence($excerpt) {
        if (isset($excerpt[1]) and in_array($excerpt[1], $this->specialCharacters)) {
            return array('markup' => $excerpt[1], 'extent' => 2,);
        }
    }
    protected function inlineLessThan() {
        return array('markup' => '&lt;', 'extent' => 1,);
    }
    protected function inlineGreaterThan() {
        return array('markup' => '&gt;', 'extent' => 1,);
    }
    protected function inlineQuotationMark() {
        return array('markup' => '&quot;', 'extent' => 1,);
    }
    protected function inlineUrlTag($excerpt) {
        if (strpos($excerpt, '>') !== false and preg_match('/^<(https?:[\/]{2}[^\s]+?)>/i', $excerpt, $matches)) {
            $url = str_replace(array('&', '<'), array('&amp;', '&lt;'), $matches[1]);
            return array('extent' => strlen($matches[0]), 'element' => array('name' => 'a', 'text' => $url, 'attributes' => array('href' => $url,),),);
        }
    }
    protected function inlineEmailTag($excerpt) {
        if (strpos($excerpt, '>') !== false and preg_match('/^<((mailto:)?\S+?@\S+?)>/i', $excerpt, $matches)) {
            $url = $matches[1];
            if (!isset($matches[2])) {
                $url = 'mailto:' . $url;
            }
            return array('extent' => strlen($matches[0]), 'element' => array('name' => 'a', 'text' => $matches[1], 'attributes' => array('href' => $url,),),);
        }
    }
    protected function inlineTag($excerpt) {
        if ($this->markupEscaped) {
            return;
        }
        if (strpos($excerpt, '>') !== false and preg_match('/^<\/?\w.*?>/s', $excerpt, $matches)) {
            return array('markup' => $matches[0], 'extent' => strlen($matches[0]),);
        }
    }
    protected function inlineCode($excerpt) {
        $marker = $excerpt[0];
        if (preg_match('/^(' . $marker . '+)[ ]*(.+?)[ ]*(?<!' . $marker . ')\1(?!' . $marker . ')/s', $excerpt, $matches)) {
            $text = $matches[2];
            $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
            $text = preg_replace("/[ ]*\n/", ' ', $text);
            return array('extent' => strlen($matches[0]), 'element' => array('name' => 'code', 'text' => $text,),);
        }
    }
    protected function inlineImage($excerpt) {
        if (!isset($excerpt[1]) or $excerpt[1] !== '[') {
            return;
        }
        $excerpt = substr($excerpt, 1);
        $Inline = $this->inlineLink($excerpt);
        if ($Inline === null) {
            return;
        }
        $Inline['extent']++;
        $Inline['element'] = array('name' => 'img', 'attributes' => array('src' => $Inline['element']['attributes']['href'], 'alt' => $Inline['element']['text'], 'title' => $Inline['element']['attributes']['title'],),);
        return $Inline;
    }
    protected function inlineLink($excerpt) {
        $Element = array('name' => 'a', 'handler' => 'line', 'text' => null, 'attributes' => array('href' => null, 'title' => null,),);
        $extent = 0;
        $remainder = $excerpt;
        if (preg_match('/\[((?:[^][]|(?R))*)\]/', $remainder, $matches)) {
            $Element['text'] = $matches[1];
            $extent+= strlen($matches[0]);
            $remainder = substr($remainder, $extent);
        } else {
            return;
        }
        if (preg_match('/^\([ ]*([^ ]+?)(?:[ ]+(".+?"|\'.+?\'))?[ ]*\)/', $remainder, $matches)) {
            $Element['attributes']['href'] = $matches[1];
            if (isset($matches[2])) {
                $Element['attributes']['title'] = substr($matches[2], 1, -1);
            }
            $extent+= strlen($matches[0]);
        } else {
            if (preg_match('/^\s*\[(.*?)\]/', $remainder, $matches)) {
                $definition = $matches[1] ? $matches[1] : $Element['text'];
                $definition = strtolower($definition);
                $extent+= strlen($matches[0]);
            } else {
                $definition = strtolower($Element['text']);
            }
            if (!isset($this->Definitions['Reference'][$definition])) {
                return;
            }
            $Definition = $this->Definitions['Reference'][$definition];
            $Element['attributes']['href'] = $Definition['url'];
            $Element['attributes']['title'] = $Definition['title'];
        }
        $Element['attributes']['href'] = str_replace(array('&', '<'), array('&amp;', '&lt;'), $Element['attributes']['href']);
        return array('extent' => $extent, 'element' => $Element,);
    }
    protected function inlineEmphasis($excerpt) {
        if (!isset($excerpt[1])) {
            return;
        }
        $marker = $excerpt[0];
        if ($excerpt[1] === $marker and preg_match($this->StrongRegex[$marker], $excerpt, $matches)) {
            $emphasis = 'strong';
        } elseif (preg_match($this->EmRegex[$marker], $excerpt, $matches)) {
            $emphasis = 'em';
        } else {
            return;
        }
        return array('extent' => strlen($matches[0]), 'element' => array('name' => $emphasis, 'handler' => 'line', 'text' => $matches[1],),);
    }
    protected $unmarkedInlineTypes = array("  \n" => 'Break', '://' => 'Url',);
    protected function unmarkedText($text) {
        foreach ($this->unmarkedInlineTypes as $snippet => $inlineType) {
            if (strpos($text, $snippet) !== false) {
                $text = $this->{'unmarkedInline' . $inlineType}($text);
            }
        }
        return $text;
    }
    protected function unmarkedInlineBreak($text) {
        if ($this->breaksEnabled) {
            $text = preg_replace('/[ ]*\n/', "<br />\n", $text);
        } else {
            $text = preg_replace('/(?:[ ][ ]+|[ ]*\\\\)\n/', "<br />\n", $text);
            $text = str_replace(" \n", "\n", $text);
        }
        return $text;
    }
    protected function unmarkedInlineUrl($text) {
        if ($this->urlsLinked !== true) {
            return $text;
        }
        $re = '/\bhttps?:[\/]{2}[^\s<]+\b\/*/ui';
        $offset = 0;
        while (strpos($text, '://', $offset) and preg_match($re, $text, $matches, PREG_OFFSET_CAPTURE, $offset)) {
            $url = $matches[0][0];
            $urlLength = strlen($url);
            $urlPosition = $matches[0][1];
            $markup = '<a href="' . $url . '">' . $url . '</a>';
            $markupLength = strlen($markup);
            $text = substr_replace($text, $markup, $urlPosition, $urlLength);
            $offset = $urlPosition + $markupLength;
        }
        return $text;
    }
    protected function li($lines) {
        $markup = $this->lines($lines);
        $trimmedMarkup = trim($markup);
        if (!in_array('', $lines) and substr($trimmedMarkup, 0, 3) === '<p>') {
            $markup = $trimmedMarkup;
            $markup = substr($markup, 3);
            $position = strpos($markup, "</p>");
            $markup = substr_replace($markup, '', $position, 4);
        }
        return $markup;
    }
    static function instance($name = 'default') {
        if (isset(self::$instances[$name])) {
            return self::$instances[$name];
        }
        $instance = new self();
        self::$instances[$name] = $instance;
        return $instance;
    }
    private static $instances = array();
    function parse($text) {
        $markup = $this->text($text);
        return $markup;
    }
    protected $Definitions;
    protected $specialCharacters = array('\\', '`', '*', '_', '{', '}', '[', ']', '(', ')', '>', '#', '+', '-', '.', '!',);
    protected $StrongRegex = array('*' => '/^[*]{2}((?:\\\\\*|[^*]|[*][^*]*[*])+?)[*]{2}(?![*])/s', '_' => '/^__((?:\\\\_|[^_]|_[^_]*_)+?)__(?!_)/us',);
    protected $EmRegex = array('*' => '/^[*]((?:\\\\\*|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*])/s', '_' => '/^_((?:\\\\_|[^_]|__[^_]*__)+?)_(?!_)\b/us',);
    protected $voidElements = array('area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source',);
    protected $textLevelElements = array('a', 'br', 'bdo', 'abbr', 'blink', 'nextid', 'acronym', 'basefont', 'b', 'em', 'big', 'cite', 'small', 'spacer', 'listing', 'i', 'rp', 'del', 'code', 'strike', 'marquee', 'q', 'rt', 'ins', 'font', 'strong', 's', 'tt', 'sub', 'mark', 'u', 'xm', 'sup', 'nobr', 'var', 'ruby', 'wbr', 'span', 'time',);
}