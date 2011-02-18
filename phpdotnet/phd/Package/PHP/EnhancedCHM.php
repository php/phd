<?php
namespace phpdotnet\phd;
/*  $Id: EnhancedCHM.php 307979 2011-02-03 17:21:14Z rquadling $ */

class Package_PHP_EnhancedCHM extends Package_PHP_CHM
{
    // Do not bother processing notes if we haven't got them this time.
    protected $haveNotes = false;

    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PHP-EnhancedCHM");
    }

    public function update($event, $val = null) {
        switch($event) {
        case Render::INIT:
            parent::update($event, $val);

            // Get http://www.php.net/backend/notes/all.bz2 and save it.
            v('Downloading usernotes.', VERBOSE_MESSAGES);
            if (false === ($userNotesArchive = file_get_contents('http://www.php.net/backend/notes/all.bz2'))) {
                v('Failed to download usernotes.', E_USER_ERROR);
                break;
            }

            // Save the usernotes.
            v('Saving usernotes.', VERBOSE_MESSAGES);
            if (false === file_put_contents(Config::output_dir() . 'all.bz2', $userNotesArchive)) {
                v('Failed to save usernotes.', E_USER_ERROR);
                break;
            }

            // Decompress the usernotes.
            v('Decompressing usernotes.', VERBOSE_MESSAGES);
            if (false === strpos(exec('bzip2.exe -dfkv ' . Config::output_dir() . 'all.bz2 2>&1'), 'all.bz2: done')) {
                v('Failed to decompress usernotes.' . PHP_EOL . implode(PHP_EOL, $output), E_USER_ERROR);
                break;
            }

            // Extract the usernotes and store them by page and date.
            v('Preparing usernotes.', VERBOSE_MESSAGES);

            // Create usernotes directory.
            $this->userNotesDir = Config::output_dir() . 'usernotes' . DIRECTORY_SEPARATOR;
            if(!file_exists($this->userNotesDir) || is_file($this->userNotesDir)) {
                mkdir($this->userNotesDir) or v("Can't create the usernotes directory", E_USER_ERROR);
            }

            // Remove any existing files.
            foreach(glob($this->userNotesDir . '*' . DIRECTORY_SEPARATOR . '*') as $sectionFile) {
                unlink($sectionFile);
            }

            // Decompress the 'all' file into single files - one file per sectionid.
            $userNotesFile = fopen(Config::output_dir() . 'all', 'rt');
            while($userNotesFile && !feof($userNotesFile) && false !== ($userNote = fgetcsv($userNotesFile, 0, '|'))) {
                // Usernote index
                // 0 = Note ID
                // 1 = Section ID
                // 2 = Rate
                // 3 = Timestamp
                // 4 = User
                // 5 = Note

                $sectionHash = md5($userNote[1]);
                $sectionDir = $this->userNotesDir . $sectionHash[0];

                if (!file_exists($sectionDir)) {
                    mkdir($sectionDir);
                }

                file_put_contents($sectionDir . DIRECTORY_SEPARATOR . $sectionHash, implode('|', $userNote) . PHP_EOL, FILE_APPEND);
            }

            fclose($userNotesFile);

            $this->haveNotes = true;
            v('Usernotes prepared.', VERBOSE_MESSAGES);

            // Use classes rather than colors.
            ini_set('highlight.comment', 'comment');
            ini_set('highlight.default', 'default');
            ini_set('highlight.keyword', 'keyword');
            ini_set('highlight.string',  'string');
            ini_set('highlight.html',    'html');

            break;

        default:
            parent::update($event, $val);
        }
    }

    public function footer($id) {
        $footer = parent::footer($id);

        // Find usernotes file.
        $idHash = md5($id);
        $userNotesFile = $this->userNotesDir . $idHash[0] . DIRECTORY_SEPARATOR . $idHash;

        if (!file_exists($userNotesFile)) {
            $notes = ' <div class="note">There are no user contributed notes for this page.</div>';
        } else {
            $notes = '';

            foreach(file($userNotesFile) as $userNote) {
                list($noteId, $noteSection, $noteRate, $noteTimestamp, $noteUser, $noteText) = explode('|', $userNote);

                if ($noteUser) {
                    $noteUser = '<strong class="user">' . htmlspecialchars($noteUser) . '</strong>';
                }
                $noteDate = '<a href="#' . $noteId . '" class="date">' . date("d-M-Y h:i", $noteTimestamp) . '</a>';
                $anchor   = '<a name="' . $noteId . '""></a>';

                $noteText = str_replace(
                    array(
                        '&nbsp;',
                        '<br />',
                        '<font color="',        // for PHP 4
                        '<span style="color: ', // from PHP 5.0.0RC1
                        '</font>',
                        "\n ",
                        '  ',
                        '  '
                    ),
                    array(
                        ' ',
                        "<br />\n",
                        '<span class="',
                        '<span class="',
                        '</span>',
                        "\n&nbsp;",
                        '&nbsp; ',
                        '&nbsp; '
                    ),
                    preg_replace(
                        '!((mailto:|(http|ftp|nntp|news):\/\/).*?)(\s|<|\)|"|\\\\|\'|$)!',
                        '<a href="\1" rel="nofollow" target="_blank">\1</a>\4',
                        highlight_string(trim(base64_decode($noteText)), true))
                    );

                $notes .= <<< END_NOTE
  {$anchor}
  <div class="note">
   {$noteUser}
   {$noteDate}
   <div class="text">
    <div class="phpcode">
{$noteText}
    </div>
   </div>
  </div>
 <div class="foot"></div>

END_NOTE;
            }

            $notes = '<div id="allnotes">' . $notes . '</div>';
        }

        return <<< USER_NOTES
<div id="usernotes">
 <div class="head">
  <h3 class="title">User Contributed Notes</h3>
 </div>
{$notes}
</div>
{$footer}
USER_NOTES;

    }
}
