<?php
require('stopwords.php');
require('class.spellcheck.php');

class Tag {
    private $_stopwords;
    
    public function __construct()
    {
        $this->sp = new SpellCheck();
        $this->_stopwords = stopword_list();
    }

    public function extract_tags($data)
    {
        $str_array = array();
        if (!is_array($data)) {
            $data = array($data);
        }
        $str = implode(' ', $data);
        $nopunct = $this->_remove_punctuation($str);
        $words = explode(' ', $nopunct);
        $words = $this->_remove_stop_words($words);
        $words = $this->_remove_duplicates($words);
        $words = $this->_remove_noise_words($words);
        $words = $this->sp->check($words);
        return $words;
    }

    private function _remove_punctuation($words)
    {
        $nopunct = $words;
        $nopunct = preg_replace("/[^\w\s]/", '', $nopunct);
        $nopunct = preg_replace("/\s+/", ' ', $nopunct);
        return strtolower($nopunct);
    }

    private function _remove_stop_words($words)
    {
        $filtered = array();
        foreach ($words as $word) {
            if ($word && array_search($word, $this->_stopwords) === false) {
                $filtered[] = $word;
            }
        }
        return $filtered;    
    }

    private function _remove_duplicates($words)
    {
        $dup_free = array();
        foreach ($words as $word) {
            if (array_search($word, $dup_free) === false) {
                $dup_free[] = $word;
            }
        }
        return $dup_free;
    }

    private function _remove_noise_words($words)
    {
        $filtered = array();
        if (!empty($words)) {
            foreach ($words as $word) {
                if ($this->_passes_test($word)) {
                    $filtered[] = $word;
                }
            }
        }
        return $filtered;
    }

    private function _passes_test($word)
    {
        $pass = true;
        if (strlen($word) > 28) { // longer than antidisestablishmentarianism... fail!
            $pass = false;
        }
        elseif (strpos($word, '_') !== false) { // contains underscore... fail!
            $pass = false;
        }
        elseif (preg_match("/[0-9]/", $word)) { // contains digits... fail!
            $pass = false;
        }
        return $pass;
    }

}
?>
