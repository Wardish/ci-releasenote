<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
use Michelf\MarkdownExtra;
class Release_note {

    private $_release_note_path = NULL;
    private $_release_note_regex = NULL;

    public function __construct($config = array())
    {
        $this->_release_note_path = APPPATH . 'release_notes/';
        //$this->_release_note_regex = '/^\d{3}_(\w+)$/';
        $this->_release_note_regex = '/^\d{14}_(\w+)$/';
    }

    // --------------------------------------------------------------------
    /**
     * Retrieves list of available release_note scripts
     *
     * @return  array   list of release_note file paths sorted by version
     */
    public function find_release_notes()
    {
        $release_notes = array();

        // Load all *_*.md files in the release_notes path
        foreach (glob($this->_release_note_path.'*_*.md') as $file)
        {
            $name = basename($file, '.md');

            // Filter out non-release_note files
            if (preg_match($this->_release_note_regex, $name))
            {
                $number = $this->_get_release_note_number($name);

                // There cannot be duplicate release_note numbers
                if (isset($release_notes[$number]))
                {
                    $this->_error_string = sprintf($this->lang->line('release_note_multiple_version'), $number);
                    show_error($this->_error_string);
                }

                $date = DateTime::createFromFormat('YmdHis', $number);

                //html取得
                $html = $this->_markdown_to_html($file);
                //DOM捜査
                $xpath = $this->_get_xpath($html);
                $title = $this->_get_title($xpath);
                $release_notes[$number] = (object)array(
                    'title' => $title,
                    'date' => $date->format('Y/m/d　H:i'),
                    'name' => $name,
                    'file' => $file,
                    'html' => $html
                );
            }
        }

        krsort($release_notes);
        return $release_notes;
    }
    // --------------------------------------------------------------------

    /**
     * Extracts the release_note number from a filename
     *
     * @param   string  $release_note
     * @return  string  Numeric portion of a release_note filename
     */
    protected function _get_release_note_number($release_note)
    {
        return sscanf($release_note, '%[0-9]+', $number)
            ? $number : '0';
    }
    // --------------------------------------------------------------------

    protected function _get_xpath($html)
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace("php", "http://php.net/xpath");
        $xpath->registerPHPFunctions();

        return $xpath;
    }
    // --------------------------------------------------------------------

    protected function _get_title(&$xpath)
    {

        $node = $xpath->query('//*[@id="title"]')->item(0);
        if ( $node === null) {
            $node = $xpath->query('//h1[1]')->item(0);
        }
        if ( $node !== null) {
            return $node->nodeValue;
        }

        return null;
    }
    // --------------------------------------------------------------------

    protected function _markdown_to_html($file)
    {
        $text = file_get_contents($file);
        $html = MarkdownExtra::defaultTransform($text);

        return $html;
    }

}